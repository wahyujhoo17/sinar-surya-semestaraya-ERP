<?php

namespace App\Services;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DirekturUtamaService
{
    /**
     * Get direktur utama from user with role 'Direktur Utama'
     * If multiple directors exist, take the first one
     * Uses caching for better performance
     */
    public static function getDirekturUtama()
    {
        try {
            // Try cache first (cache for 5 minutes for better responsiveness)
            return Cache::remember('direktur_utama', 300, function () {
                return self::fetchDirekturUtama();
            });
        } catch (\Exception $e) {
            Log::error('Error getting direktur utama: ' . $e->getMessage());
            return 'Direktur Utama';
        }
    }

    /**
     * Get direktur utama object with full details
     * Returns User model with karyawan relation
     */
    public static function getDirekturUtamaUser()
    {
        try {
            return Cache::remember('direktur_utama_user', 300, function () {
                // Try to get direktur utama from user with role 'Direktur Utama'
                $direktur = User::whereHas('roles', function ($query) {
                    $query->where('nama', 'Direktur Utama')
                        ->orWhere('kode', 'direktur_utama');
                })
                    ->with(['karyawan', 'roles'])
                    ->where('is_active', true)
                    ->first();

                if ($direktur && $direktur->karyawan) {
                    return $direktur;
                }

                // Fallback: get direktur utama from karyawan with jabatan 'Direktur Utama'
                $direkturKaryawan = Karyawan::whereHas('jabatan', function ($q) {
                    $q->where('nama', 'Direktur Utama');
                })
                    ->with(['user', 'jabatan'])
                    ->where('status', 'aktif')
                    ->first();

                if ($direkturKaryawan && $direkturKaryawan->user) {
                    return $direkturKaryawan->user;
                }

                return null;
            });
        } catch (\Exception $e) {
            Log::error('Error getting direktur utama user: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch direktur utama name from database
     */
    private static function fetchDirekturUtama()
    {
        // Try to get direktur utama from user with role 'Direktur Utama'
        $direktur = User::whereHas('roles', function ($query) {
            $query->where('nama', 'Direktur Utama')
                ->orWhere('kode', 'direktur_utama');
        })
            ->with('karyawan')
            ->where('is_active', true)
            ->first();

        if ($direktur && $direktur->karyawan) {
            return $direktur->karyawan->nama_lengkap;
        }

        // Fallback: get direktur utama from karyawan with jabatan 'Direktur Utama'
        $direkturKaryawan = Karyawan::whereHas('jabatan', function ($q) {
            $q->where('nama', 'Direktur Utama');
        })
            ->where('status', 'aktif')
            ->first();

        if ($direkturKaryawan) {
            return $direkturKaryawan->nama_lengkap;
        }

        // Final fallback
        return 'Direktur Utama';
    }

    /**
     * Clear direktur utama cache
     * Call this when direktur utama data is updated
     */
    public static function clearCache()
    {
        Cache::forget('direktur_utama');
        Cache::forget('direktur_utama_user');
    }

    /**
     * Get direktur utama for specific company/PT
     * For multi-PT system
     */
    public static function getDirekturUtamaByCompany($companyId = null)
    {
        try {
            $cacheKey = "direktur_utama_company_{$companyId}";

            return Cache::remember($cacheKey, 300, function () use ($companyId) {
                $query = User::whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('nama', 'Direktur Utama')
                        ->orWhere('kode', 'direktur_utama');
                })
                    ->with('karyawan')
                    ->where('is_active', true);

                // If company filtering is needed in the future
                if ($companyId) {
                    $query->whereHas('karyawan', function ($karyawanQuery) use ($companyId) {
                        // Add company filter if company_id field exists in karyawan table
                        // $karyawanQuery->where('company_id', $companyId);
                    });
                }

                $direktur = $query->first();

                if ($direktur && $direktur->karyawan) {
                    return $direktur->karyawan->nama_lengkap;
                }

                // Fallback to jabatan-based search
                $karyawanQuery = Karyawan::whereHas('jabatan', function ($q) {
                    $q->where('nama', 'Direktur Utama');
                })
                    ->where('status', 'aktif');

                // Add company filter if needed
                if ($companyId) {
                    // $karyawanQuery->where('company_id', $companyId);
                }

                $direkturKaryawan = $karyawanQuery->first();

                if ($direkturKaryawan) {
                    return $direkturKaryawan->nama_lengkap;
                }

                return 'Direktur Utama';
            });
        } catch (\Exception $e) {
            Log::error('Error getting direktur utama by company: ' . $e->getMessage());
            return 'Direktur Utama';
        }
    }
}
