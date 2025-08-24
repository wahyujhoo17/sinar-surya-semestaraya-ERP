<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DirekturUtamaRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Direktur Utama role if it doesn't exist
        Role::firstOrCreate(
            ['kode' => 'direktur_utama'],
            [
                'nama' => 'Direktur Utama',
                'deskripsi' => 'Direktur Utama/CEO perusahaan dengan akses penuh untuk persetujuan dokumen'
            ]
        );

        // Also ensure admin role exists (if not already)
        Role::firstOrCreate(
            ['kode' => 'admin'],
            [
                'nama' => 'Administrator',
                'deskripsi' => 'Akses penuh ke seluruh sistem'
            ]
        );

        $this->command->info('Direktur Utama role created successfully!');
    }
}
