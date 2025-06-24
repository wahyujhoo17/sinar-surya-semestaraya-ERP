<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RekonsiliasiBankHistory extends Model
{
    use HasFactory;

    protected $table = 'rekonsiliasi_bank_history';

    protected $fillable = [
        'reconciliation_id',
        'rekening_bank_id',
        'periode',
        'tahun',
        'bulan',
        'erp_balance',
        'bank_balance',
        'difference',
        'status',
        'matched_transactions',
        'unmatched_erp_transactions',
        'unmatched_bank_transactions',
        'summary_statistics',
        'reconciled_by',
        'reconciled_at',
        'file_uploaded',
        'bank_statement_file',
        'notes',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'periode' => 'date',
        'tahun' => 'integer',
        'erp_balance' => 'decimal:2',
        'bank_balance' => 'decimal:2',
        'difference' => 'decimal:2',
        'matched_transactions' => 'array',
        'unmatched_erp_transactions' => 'array',
        'unmatched_bank_transactions' => 'array',
        'summary_statistics' => 'array',
        'reconciled_at' => 'datetime',
        'approved_at' => 'datetime'
    ];

    /**
     * Relasi ke rekening bank
     */
    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id');
    }

    /**
     * Relasi ke user yang membuat
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang approve
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    /**
     * Accessor untuk format periode
     */
    public function getPeriodeFormattedAttribute()
    {
        return Carbon::parse($this->periode)->format('F Y');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Reconciled' => 'bg-green-100 text-green-800',
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Rejected' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if reconciliation is balanced
     */
    public function isBalanced()
    {
        return $this->difference == 0;
    }

    /**
     * Get unmatched count
     */
    public function getUnmatchedCountAttribute()
    {
        $erpCount = is_array($this->unmatched_erp_transactions) ? count($this->unmatched_erp_transactions) : 0;
        $bankCount = is_array($this->unmatched_bank_transactions) ? count($this->unmatched_bank_transactions) : 0;

        return $erpCount + $bankCount;
    }
}