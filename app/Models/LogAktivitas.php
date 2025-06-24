<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'data_id',
        'ip_address',
        'detail'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get formatted activity name
     */
    public function getFormattedAktivitasAttribute()
    {
        $activities = [
            'create' => 'Buat',
            'update' => 'Ubah',
            'delete' => 'Hapus',
            'view' => 'Lihat',
            'access' => 'Akses',
            'akses' => 'Akses',
            'tambah' => 'Tambah',
            'ubah' => 'Ubah',
            'hapus' => 'Hapus',
            'change_status' => 'Ubah Status',
            'login' => 'Login',
            'logout' => 'Logout',
            'export' => 'Ekspor',
            'import' => 'Impor',
            'approve' => 'Setujui',
            'reject' => 'Tolak',
            'submit' => 'Kirim',
            'cancel' => 'Batal',
            'process' => 'Proses',
            'complete' => 'Selesai'
        ];

        return $activities[$this->aktivitas] ?? ucfirst(str_replace('_', ' ', $this->aktivitas));
    }

    /**
     * Get formatted module name
     */
    public function getFormattedModulAttribute()
    {
        $modules = [
            'sales_order' => 'Sales Order',
            'purchase_order' => 'Purchase Order',
            'permintaan_pembelian' => 'Permintaan Pembelian',
            'penerimaan_barang' => 'Penerimaan Barang',
            'delivery_order' => 'Delivery Order',
            'invoice' => 'Invoice',
            'quotation' => 'Quotation',
            'customer' => 'Customer',
            'supplier' => 'Supplier',
            'produk' => 'Produk',
            'kategori_produk' => 'Kategori Produk',
            'gudang' => 'Gudang',
            'satuan' => 'Satuan',
            'stok_barang' => 'Stok Barang',
            'transfer_gudang' => 'Transfer Gudang',
            'penyesuaian_stok' => 'Penyesuaian Stok',
            'bom' => 'Bill of Material',
            'work_order' => 'Work Order',
            'perencanaan_produksi' => 'Perencanaan Produksi',
            'karyawan' => 'Karyawan',
            'penggajian' => 'Penggajian',
            'kas_dan_bank' => 'Kas & Bank',
            'hutang_usaha' => 'Hutang Usaha',
            'piutang_usaha' => 'Piutang Usaha',
            'jurnal_umum' => 'Jurnal Umum',
            'coa' => 'Chart of Accounts',
            'prospek' => 'Prospek & Lead',
            'aktivitas_crm' => 'Aktivitas CRM',
            'pipeline' => 'Pipeline Penjualan',
            'management_pengguna' => 'Management Pengguna',
            'log_aktivitas' => 'Log Aktivitas'
        ];

        return $modules[$this->modul] ?? ucfirst(str_replace('_', ' ', $this->modul));
    }

    /**
     * Get activity badge color based on activity type
     */
    public function getActivityBadgeColorAttribute()
    {
        $colors = [
            'create' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'tambah' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'update' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'ubah' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'change_status' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'delete' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'hapus' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'view' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
            'akses' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
            'access' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
            'login' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'logout' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'export' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'import' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'approve' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'reject' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'submit' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'cancel' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'process' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'complete' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
        ];

        return $colors[$this->aktivitas] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }

    /**
     * Get parsed detail as array if it's JSON, otherwise return as string
     */
    public function getParsedDetailAttribute()
    {
        if (empty($this->detail)) {
            return null;
        }

        try {
            $decoded = json_decode($this->detail, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        } catch (\Exception $e) {
            // Log the error but don't break the application
            Log::warning('Failed to parse JSON detail for LogAktivitas ID: ' . $this->id, [
                'detail' => $this->detail,
                'error' => $e->getMessage()
            ]);
        }

        return $this->detail;
    }

    /**
     * Check if detail is JSON format
     */
    public function getIsJsonDetailAttribute()
    {
        if (empty($this->detail)) {
            return false;
        }

        try {
            json_decode($this->detail);
            return json_last_error() === JSON_ERROR_NONE;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get formatted detail for display
     */
    public function getFormattedDetailAttribute()
    {
        if (empty($this->detail)) {
            return 'Tidak ada detail tambahan';
        }

        // Try to use ActivityLogHelper function if exists
        if (function_exists('formatActivityLog')) {
            try {
                return formatActivityLog($this);
            } catch (\Exception $e) {
                Log::warning('Failed to format activity log for LogAktivitas ID: ' . $this->id, [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $this->detail;
    }

    /**
     * Get a human-readable summary of the log
     */
    public function getSummaryAttribute()
    {
        $user = $this->user ? $this->user->name : 'Sistem';
        $activity = $this->formatted_aktivitas;
        $module = $this->formatted_modul;

        $summary = "{$user} melakukan {$activity} pada {$module}";

        if ($this->data_id) {
            $summary .= " (ID: {$this->data_id})";
        }

        return $summary;
    }

    /**
     * Get time elapsed since log creation in human readable format
     */
    public function getTimeElapsedAttribute()
    {
        if (!$this->created_at) {
            return 'Waktu tidak tersedia';
        }

        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by module
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('modul', 'like', "%{$module}%");
    }

    /**
     * Scope for filtering by activity
     */
    public function scopeByActivity($query, $activity)
    {
        return $query->where('aktivitas', 'like', "%{$activity}%");
    }

    /**
     * Scope for recent logs with optimized selection
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->select(['id', 'user_id', 'aktivitas', 'modul', 'data_id', 'ip_address', 'created_at'])
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }

    /**
     * Scope for efficient listing with minimal data
     */
    public function scopeForListing($query)
    {
        return $query->select(['id', 'user_id', 'aktivitas', 'modul', 'data_id', 'ip_address', 'detail', 'created_at'])
            ->with(['user:id,name,email']);
    }

    /**
     * Get logs for the same data record
     */
    public function getRelatedLogs($limit = 10)
    {
        if (!$this->data_id || !$this->modul) {
            return collect();
        }

        return static::with(['user:id,name,email'])
            ->select(['id', 'user_id', 'aktivitas', 'modul', 'data_id', 'created_at'])
            ->where('data_id', $this->data_id)
            ->where('modul', $this->modul)
            ->where('id', '!=', $this->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent logs from the same user
     */
    public function getUserRecentLogs($limit = 5)
    {
        return static::with(['user:id,name,email'])
            ->select(['id', 'user_id', 'aktivitas', 'modul', 'data_id', 'created_at'])
            ->where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
