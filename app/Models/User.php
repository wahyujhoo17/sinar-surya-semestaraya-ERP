<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relasi ke Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('kode', $role);
        }

        return $role->intersect($this->roles)->count() > 0;
    }

    /**
     * Cek apakah user memiliki permission tertentu
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('kode', $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Relasi ke log aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get user's photo URL from their karyawan profile
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->karyawan && $this->karyawan->foto) {
            return asset('storage/' . $this->karyawan->foto);
        }
        return null;
    }

    /**
     * Get user's initials from their name
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break; // Limit to 2 initials
            }
        }

        return $initials ?: strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Get user's display name (karyawan name if available, otherwise user name)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->karyawan && $this->karyawan->nama_lengkap) {
            return $this->karyawan->nama_lengkap;
        }
        return $this->name;
    }

    /**
     * Get user's email for display (karyawan email if available, otherwise user email)
     */
    public function getDisplayEmailAttribute()
    {
        if ($this->karyawan && $this->karyawan->email) {
            return $this->karyawan->email;
        }
        return $this->email;
    }
}
