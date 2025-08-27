<?php

namespace App\Observers;

use App\Models\Karyawan;
use App\Services\DirekturUtamaService;
use Illuminate\Support\Facades\Log;

class KaryawanObserver
{
    /**
     * Handle the Karyawan "updated" event.
     */
    public function updated(Karyawan $karyawan)
    {
        // Load jabatan relation if not already loaded
        if (!$karyawan->relationLoaded('jabatan')) {
            $karyawan->load('jabatan');
        }

        // Clear direktur utama cache if this karyawan has "Direktur Utama" jabatan
        if ($karyawan->jabatan && $karyawan->jabatan->nama === 'Direktur Utama') {
            DirekturUtamaService::clearCache();
            Log::info('Direktur Utama cache cleared due to karyawan update', [
                'karyawan_id' => $karyawan->id,
                'karyawan_nama' => $karyawan->nama_lengkap,
                'jabatan' => $karyawan->jabatan->nama
            ]);
        }
    }

    /**
     * Handle the Karyawan "created" event.
     */
    public function created(Karyawan $karyawan)
    {
        // Load jabatan relation if not already loaded
        if (!$karyawan->relationLoaded('jabatan')) {
            $karyawan->load('jabatan');
        }

        // Clear cache if new direktur utama is created
        if ($karyawan->jabatan && $karyawan->jabatan->nama === 'Direktur Utama') {
            DirekturUtamaService::clearCache();
            Log::info('Direktur Utama cache cleared due to new karyawan creation', [
                'karyawan_id' => $karyawan->id,
                'karyawan_nama' => $karyawan->nama_lengkap,
                'jabatan' => $karyawan->jabatan->nama
            ]);
        }
    }

    /**
     * Handle the Karyawan "deleted" event.
     */
    public function deleted(Karyawan $karyawan)
    {
        // Load jabatan relation if not already loaded
        if (!$karyawan->relationLoaded('jabatan')) {
            $karyawan->load('jabatan');
        }

        // Clear cache if direktur utama is deleted
        if ($karyawan->jabatan && $karyawan->jabatan->nama === 'Direktur Utama') {
            DirekturUtamaService::clearCache();
            Log::info('Direktur Utama cache cleared due to karyawan deletion', [
                'karyawan_id' => $karyawan->id,
                'karyawan_nama' => $karyawan->nama_lengkap,
                'jabatan' => $karyawan->jabatan->nama
            ]);
        }
    }
}
