<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPajak extends Model
{
    use HasFactory;

    protected $table = 'laporan_pajaks';

    protected $fillable = [
        'jenis_pajak',
        'no_faktur_pajak',
        'nomor',
        'tanggal',
        'tanggal_faktur',
        'tanggal_jatuh_tempo',
        'periode',
        'periode_awal',
        'periode_akhir',
        'dasar_pengenaan_pajak',
        'tarif_pajak',
        'jumlah_pajak',
        'nilai',
        'status',
        'status_pembayaran',
        'npwp',
        'keterangan',
        'file_path',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_faktur' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'periode' => 'date',
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'dasar_pengenaan_pajak' => 'decimal:2',
        'tarif_pajak' => 'decimal:2',
        'jumlah_pajak' => 'decimal:2',
        'nilai' => 'decimal:2',
    ];

    /**
     * Get the user that created this tax report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted tax type name.
     */
    public function getFormattedJenisAttribute(): string
    {
        $types = [
            'ppn_keluaran' => 'PPN Keluaran',
            'ppn_masukan' => 'PPN Masukan',
            'pph21' => 'PPh 21',
            'pph23' => 'PPh 23',
            'pph4_ayat2' => 'PPh 4 Ayat 2',
        ];

        return $types[$this->jenis_pajak] ?? ucfirst(str_replace('_', ' ', $this->jenis_pajak));
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status === 'final' ? 'green' : 'yellow';
    }

    /**
     * Get the payment status badge color.
     */
    public function getPaymentStatusColorAttribute(): string
    {
        $colors = [
            'belum_bayar' => 'red',
            'sudah_bayar' => 'green',
            'lebih_bayar' => 'blue',
        ];

        return $colors[$this->status_pembayaran] ?? 'gray';
    }
}
