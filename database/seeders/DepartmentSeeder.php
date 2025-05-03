<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'nama' => 'Direktur Utama',
                'kode' => 'DIR',
                'deskripsi' => 'Direktur Utama',
                'parent_id' => null
            ],
            [
                'nama' => 'Keuangan & Administrasi',
                'kode' => 'FIN',
                'deskripsi' => 'Departemen Keuangan dan Administrasi',
                'parent_id' => null
            ],
            [
                'nama' => 'Pemasaran & Penjualan',
                'kode' => 'SAL',
                'deskripsi' => 'Departemen Pemasaran dan Penjualan',
                'parent_id' => null
            ],
            [
                'nama' => 'Operasional',
                'kode' => 'OPS',
                'deskripsi' => 'Departemen Operasional',
                'parent_id' => null
            ],
            [
                'nama' => 'Produksi',
                'kode' => 'PRD',
                'deskripsi' => 'Departemen Produksi',
                'parent_id' => 4
            ],
            [
                'nama' => 'Gudang & Logistik',
                'kode' => 'LOG',
                'deskripsi' => 'Departemen Gudang dan Logistik',
                'parent_id' => 4
            ],
            [
                'nama' => 'SDM & Umum',
                'kode' => 'HRD',
                'deskripsi' => 'Departemen Sumber Daya Manusia & Umum',
                'parent_id' => null
            ],
            [
                'nama' => 'Teknik & Pengembangan',
                'kode' => 'ENG',
                'deskripsi' => 'Departemen Teknik dan Pengembangan',
                'parent_id' => null
            ]
        ];

        // Masukkan department tingkat pertama dulu
        foreach ($departments as $key => $dept) {
            if ($dept['parent_id'] === null) {
                $department = Department::create([
                    'nama' => $dept['nama'],
                    'kode' => $dept['kode'],
                    'deskripsi' => $dept['deskripsi'],
                    'parent_id' => null
                ]);
                $departments[$key]['id'] = $department->id;
            }
        }

        // Kemudian masukkan department yang memiliki parent
        foreach ($departments as $dept) {
            if ($dept['parent_id'] !== null) {
                // Dapatkan ID parent yang sebenarnya
                $parentId = $departments[$dept['parent_id'] - 1]['id'];
                
                Department::create([
                    'nama' => $dept['nama'],
                    'kode' => $dept['kode'],
                    'deskripsi' => $dept['deskripsi'],
                    'parent_id' => $parentId
                ]);
            }
        }
    }
}