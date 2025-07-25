<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransaksiProject extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $fillable = [
        'project_id',
        'tanggal',
        'jenis',
        'jumlah',
        'keterangan',
        'no_bukti',
        'sumber_dana_type',
        'kas_id',
        'rekening_bank_id',
        'kategori_penggunaan',
        'related_type',
        'related_id',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    /**
     * Relasi ke Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kas
     */
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }

    /**
     * Relasi ke Rekening Bank
     */
    public function rekeningBank(): BelongsTo
    {
        return $this->belongsTo(RekeningBank::class);
    }

    /**
     * Relasi polimorfik ke dokumen terkait
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope berdasarkan jenis transaksi
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopePeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Boot method untuk auto update saldo project
     */
    protected static function boot()
    {
        parent::boot();

        // Update saldo project setelah transaksi dibuat
        static::created(function ($transaksi) {
            $transaksi->updateProjectSaldo();
        });

        // Update saldo project setelah transaksi diupdate
        static::updated(function ($transaksi) {
            $transaksi->updateProjectSaldo();
        });

        // Update saldo project setelah transaksi dihapus
        static::deleted(function ($transaksi) {
            $transaksi->updateProjectSaldo();
        });
    }

    /**
     * Update saldo project berdasarkan transaksi
     */
    public function updateProjectSaldo()
    {
        $project = $this->project;
        if (!$project) return;

        // Hitung ulang saldo project
        $totalAlokasi = $project->transaksi()->where('jenis', 'alokasi')->sum('jumlah');
        $totalPenggunaan = $project->transaksi()->where('jenis', 'penggunaan')->sum('jumlah');
        $totalPengembalian = $project->transaksi()->where('jenis', 'pengembalian')->sum('jumlah');

        // Saldo = alokasi - penggunaan + pengembalian
        $saldoBaru = $totalAlokasi - $totalPenggunaan + $totalPengembalian;

        $project->update(['saldo' => $saldoBaru]);
    }

    /**
     * Membuat jurnal otomatis untuk transaksi project
     */
    public function createAutomaticJournal()
    {
        try {
            // Dapatkan konfigurasi akun dari config/accounting.php
            $akunProject = config('accounting.project.project_expense'); // Akun biaya project
            $akunKasDefault = config('accounting.project.kas');
            $akunBankDefault = config('accounting.project.bank');

            if (!$akunProject) {
                \Log::error("Akun project belum dikonfigurasi", [
                    'transaksi_id' => $this->id,
                    'project_id' => $this->project_id
                ]);
                return false;
            }

            $entries = [];

            // Logic berdasarkan jenis transaksi
            switch ($this->jenis) {
                case 'alokasi':
                    // Debit: Project Asset/Expense, Kredit: Kas/Bank
                    $entries[] = [
                        'akun_id' => $akunProject,
                        'debit' => $this->jumlah,
                        'kredit' => 0
                    ];

                    // Tentukan akun sumber (kas atau bank)
                    $akunSumber = $this->getAkunSumber();
                    if ($akunSumber) {
                        $entries[] = [
                            'akun_id' => $akunSumber,
                            'debit' => 0,
                            'kredit' => $this->jumlah
                        ];
                    }
                    break;

                case 'pengembalian':
                    // Debit: Kas/Bank, Kredit: Project Asset/Expense
                    $akunSumber = $this->getAkunSumber();
                    if ($akunSumber) {
                        $entries[] = [
                            'akun_id' => $akunSumber,
                            'debit' => $this->jumlah,
                            'kredit' => 0
                        ];
                    }

                    $entries[] = [
                        'akun_id' => $akunProject,
                        'debit' => 0,
                        'kredit' => $this->jumlah
                    ];
                    break;
            }

            if (!empty($entries)) {
                $this->createJournalEntries(
                    $entries,
                    $this->no_bukti ?: "PROJ-{$this->id}",
                    "Transaksi project: {$this->project->nama} - {$this->keterangan}",
                    $this->tanggal->format('Y-m-d')
                );
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error creating journal for project transaction: " . $e->getMessage(), [
                'transaksi_id' => $this->id,
                'project_id' => $this->project_id
            ]);
            return false;
        }
    }

    /**
     * Mendapatkan akun sumber dana (kas atau bank)
     */
    private function getAkunSumber()
    {
        if ($this->sumber_dana_type === 'kas' && $this->kas_id) {
            $akunKas = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\Kas')
                ->where('ref_id', $this->kas_id)
                ->first();
            return $akunKas ? $akunKas->id : config('accounting.project.kas');
        } elseif ($this->sumber_dana_type === 'bank' && $this->rekening_bank_id) {
            $akunBank = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\RekeningBank')
                ->where('ref_id', $this->rekening_bank_id)
                ->first();
            return $akunBank ? $akunBank->id : config('accounting.project.bank');
        }

        return null;
    }
}
