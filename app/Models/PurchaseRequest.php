<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;
    
    protected $table = 'purchase_request';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'user_id',
        'department_id',
        'catatan',
        'status' // 'draft', 'diajukan', 'disetujui', 'ditolak', 'selesai'
    ];
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    /**
     * Relasi ke Detail Purchase Request
     */
    public function details()
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'pr_id');
    }
    
    /**
     * Relasi ke Purchase Order
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'pr_id');
    }
}