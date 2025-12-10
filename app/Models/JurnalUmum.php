<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PeriodeAkuntansi;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'no_referensi',
        'akun_id',
        'debit',
        'kredit',
        'keterangan',
        'jenis_jurnal',
        'sumber',
        'ref_type',
        'ref_id',
        'user_id',
        'is_posted',
        'posted_at',
        'posted_by',
        'is_reversed',
        'reversal_ref',
        'approval_notes'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'posted_at' => 'datetime',
        'is_posted' => 'boolean',
        'is_reversed' => 'boolean',
    ];

    public function akun()
    {
        return $this->belongsTo(AkunAkuntansi::class, 'akun_id');
    }

    public function referensi()
    {
        return $this->morphTo('referensi', 'ref_type', 'ref_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi untuk mendapatkan details jurnal dengan no_referensi yang sama
     */
    public function details()
    {
        return $this->hasMany(JurnalUmum::class, 'no_referensi', 'no_referensi')
            ->where('tanggal', $this->tanggal);
    }

    /**
     * Scope untuk jurnal penyesuaian
     */
    public function scopePenyesuaian($query)
    {
        return $query->where('jenis_jurnal', 'penyesuaian');
    }

    /**
     * Scope untuk jurnal umum
     */
    public function scopeUmum($query)
    {
        return $query->where('jenis_jurnal', 'umum');
    }

    /**
     * Get formatted jenis jurnal
     */
    public function getFormattedJenisJurnalAttribute()
    {
        $types = [
            'umum' => 'Jurnal Umum',
            'penyesuaian' => 'Jurnal Penyesuaian',
            'penutup' => 'Jurnal Penutup',
            'koreksi' => 'Jurnal Koreksi'
        ];

        return $types[$this->jenis_jurnal] ?? ucfirst($this->jenis_jurnal);
    }

    /**
     * Get display text for referensi
     */
    public function getReferensiDisplayAttribute()
    {
        if (!$this->ref_type || !$this->ref_id) {
            return $this->no_referensi ?? '-';
        }

        $refType = class_basename($this->ref_type);

        switch ($refType) {
            case 'PurchaseOrder':
                return $this->referensi ? $this->referensi->nomor : $this->no_referensi;
            case 'Invoice':
                return $this->referensi ? $this->referensi->nomor : $this->no_referensi;
            case 'SalesOrder':
                return $this->referensi ? $this->referensi->nomor : $this->no_referensi;
            case 'PenerimaanBarang':
                return $this->referensi ? $this->referensi->nomor : $this->no_referensi;
            case 'Kas':
                return $this->referensi ? 'Kas: ' . $this->referensi->nama : $this->no_referensi;
            case 'RekeningBank':
                return $this->referensi ? 'Bank: ' . $this->referensi->nama_bank . ' - ' . $this->referensi->nomor_rekening : $this->no_referensi;
            default:
                return $this->no_referensi ?? '-';
        }
    }

    /**
     * Relasi ke user yang posting
     */
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Scope untuk jurnal yang sudah diposting
     */
    public function scopePosted($query)
    {
        return $query->where('is_posted', true);
    }

    /**
     * Scope untuk jurnal draft
     */
    public function scopeDraft($query)
    {
        return $query->where('is_posted', false);
    }

    /**
     * Scope untuk jurnal yang belum di-reverse
     */
    public function scopeActive($query)
    {
        return $query->where('is_reversed', false);
    }

    /**
     * Method untuk posting jurnal
     */
    public function post($userId = null)
    {
        $this->update([
            'is_posted' => true,
            'posted_at' => now(),
            'posted_by' => $userId ?: auth()->id()
        ]);
    }

    /**
     * Method untuk membatalkan posting
     */
    public function unpost()
    {
        $this->update([
            'is_posted' => false,
            'posted_at' => null,
            'posted_by' => null
        ]);
    }

    /**
     * Method untuk reverse jurnal
     */
    public function reverse($reversalRef, $notes = null)
    {
        $this->update([
            'is_reversed' => true,
            'reversal_ref' => $reversalRef,
            'approval_notes' => $notes
        ]);
    }

    /**
     * Check if journal can be edited
     */
    public function canEdit()
    {
        return !$this->is_posted && !$this->is_reversed;
    }

    /**
     * Check if journal can be deleted
     */
    public function canDelete()
    {
        return !$this->is_posted && !$this->is_reversed;
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeAkuntansi::class, 'periode_id');
    }

    /**
     * Auto assign periode_id when creating jurnal entry
     */
    protected static function booted()
    {
        static::creating(function ($jurnal) {
            if (!$jurnal->periode_id && $jurnal->tanggal) {
                $periode = PeriodeAkuntansi::getPeriodeForDate($jurnal->tanggal);

                if (!$periode) {
                    // Auto create period if it doesn't exist
                    $periode = PeriodeAkuntansi::getCurrentOrCreatePeriod();
                }

                $jurnal->periode_id = $periode ? $periode->id : null;
            }
        });
    }
}
