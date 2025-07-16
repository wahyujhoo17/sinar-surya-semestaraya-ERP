<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DailyAktivitas extends Model
{
    use HasFactory;

    protected $table = 'daily_aktivitas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe_aktivitas',
        'prioritas',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deadline',
        'catatan',
        'hasil',
        'attachments',
        'lokasi',
        'peserta',
        'estimasi_durasi',
        'durasi_aktual',
        'reminder_sent',
        'reminder_at',
        'user_id',
        'assigned_to',
        'related_model',
        'related_id'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'deadline' => 'datetime',
        'reminder_at' => 'datetime',
        'attachments' => 'array',
        'reminder_sent' => 'boolean',
        'estimasi_durasi' => 'decimal:2',
        'durasi_aktual' => 'decimal:2',
    ];

    // Constants
    const TIPE_AKTIVITAS = [
        'meeting' => 'Meeting',
        'call' => 'Panggilan Telepon',
        'email' => 'Email',
        'task' => 'Tugas',
        'follow_up' => 'Follow Up',
        'presentasi' => 'Presentasi',
        'kunjungan' => 'Kunjungan',
        'training' => 'Training',
        'lainnya' => 'Lainnya'
    ];

    const PRIORITAS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
        'urgent' => 'Urgent'
    ];

    const STATUS = [
        'pending' => 'Menunggu',
        'dalam_proses' => 'Dalam Proses',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Multiple users assignment relationship
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'daily_aktivitas_users')
            ->withPivot(['role', 'notification_sent', 'notified_at'])
            ->withTimestamps();
    }

    // Get only assigned users (exclude watchers/collaborators)
    public function assignedOnlyUsers()
    {
        return $this->belongsToMany(User::class, 'daily_aktivitas_users')
            ->wherePivot('role', 'assigned')
            ->withPivot(['role', 'notification_sent', 'notified_at'])
            ->withTimestamps();
    }

    // Get watchers
    public function watchers()
    {
        return $this->belongsToMany(User::class, 'daily_aktivitas_users')
            ->wherePivot('role', 'watcher')
            ->withPivot(['role', 'notification_sent', 'notified_at'])
            ->withTimestamps();
    }

    // Get collaborators
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'daily_aktivitas_users')
            ->wherePivot('role', 'collaborator')
            ->withPivot(['role', 'notification_sent', 'notified_at'])
            ->withTimestamps();
    }

    public function relatedModel()
    {
        if (!$this->related_model || !$this->related_id) {
            return null;
        }

        $modelClass = "App\\Models\\" . ucfirst($this->related_model);

        if (class_exists($modelClass)) {
            return $modelClass::find($this->related_id);
        }

        return null;
    }

    // Accessors
    public function getTipeAktivitasLabelAttribute()
    {
        return self::TIPE_AKTIVITAS[$this->tipe_aktivitas] ?? $this->tipe_aktivitas;
    }

    public function getPrioritasLabelAttribute()
    {
        return self::PRIORITAS[$this->prioritas] ?? $this->prioritas;
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getPrioritasColorAttribute()
    {
        return match ($this->prioritas) {
            'rendah' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'sedang' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
            'tinggi' => 'text-orange-600 bg-orange-100 dark:text-orange-400 dark:bg-orange-900',
            'urgent' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
            'dalam_proses' => 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900',
            'selesai' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'dibatalkan' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->deadline &&
            Carbon::parse($this->deadline)->isPast() &&
            !in_array($this->status, ['selesai', 'dibatalkan']);
    }

    public function getIsTodayAttribute()
    {
        return Carbon::parse($this->tanggal_mulai)->isToday();
    }

    public function getIsTomorrowAttribute()
    {
        return Carbon::parse($this->tanggal_mulai)->isTomorrow();
    }

    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->deadline) return null;

        return Carbon::now()->diffInDays($this->deadline, false);
    }

    // Scopes
    public function scopeToday(Builder $query)
    {
        return $query->whereDate('tanggal_mulai', Carbon::today());
    }

    public function scopeTomorrow(Builder $query)
    {
        return $query->whereDate('tanggal_mulai', Carbon::tomorrow());
    }

    public function scopeThisWeek(Builder $query)
    {
        return $query->whereBetween('tanggal_mulai', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeOverdue(Builder $query)
    {
        return $query->where('deadline', '<', Carbon::now())
            ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeUpcoming(Builder $query)
    {
        return $query->where('tanggal_mulai', '>', Carbon::now())
            ->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    public function scopeByPrioritas(Builder $query, $prioritas)
    {
        return $query->where('prioritas', $prioritas);
    }

    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTipe(Builder $query, $tipe)
    {
        return $query->where('tipe_aktivitas', $tipe);
    }

    public function scopeAssignedTo(Builder $query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeCreatedBy(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('catatan', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
        });
    }

    // Static methods
    public static function getTipeAktivitasList()
    {
        return self::TIPE_AKTIVITAS;
    }

    public static function getPrioritasList()
    {
        return self::PRIORITAS;
    }

    public static function getStatusList()
    {
        return self::STATUS;
    }

    public static function getStatistics($userId = null)
    {
        $query = self::query();

        if ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('assigned_to', $userId);
            });
        }

        return [
            'total' => $query->count(),
            'today' => $query->clone()->today()->count(),
            'pending' => $query->clone()->where('status', 'pending')->count(),
            'dalam_proses' => $query->clone()->where('status', 'dalam_proses')->count(),
            'selesai' => $query->clone()->where('status', 'selesai')->count(),
            'overdue' => $query->clone()->overdue()->count(),
            'this_week' => $query->clone()->thisWeek()->count(),
        ];
    }
}
