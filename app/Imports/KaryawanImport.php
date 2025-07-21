<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class KaryawanImport implements ToCollection, WithHeadingRow, WithValidation
{
    public $errors = [];
    public $successCount = 0;
    public $failedCount = 0;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because of heading row and 0-based index

                try {
                    // Validate and get department
                    $department = Department::where('nama', $row['department'])->first();
                    if (!$department) {
                        $this->errors[] = "Baris {$rowNumber}: Department '{$row['department']}' tidak ditemukan";
                        $this->failedCount++;
                        continue;
                    }

                    // Validate and get jabatan
                    $jabatan = Jabatan::where('nama', $row['jabatan'])->first();
                    if (!$jabatan) {
                        $this->errors[] = "Baris {$rowNumber}: Jabatan '{$row['jabatan']}' tidak ditemukan";
                        $this->failedCount++;
                        continue;
                    }

                    // Validate and get role
                    $role = Role::where('nama', $row['role_user_admin_manager_karyawan'])->first();
                    if (!$role) {
                        $this->errors[] = "Baris {$rowNumber}: Role '{$row['role_user_admin_manager_karyawan']}' tidak ditemukan";
                        $this->failedCount++;
                        continue;
                    }

                    // Check if NIP already exists
                    if (Karyawan::where('nip', $row['nip'])->exists()) {
                        $this->errors[] = "Baris {$rowNumber}: NIP '{$row['nip']}' sudah digunakan";
                        $this->failedCount++;
                        continue;
                    }

                    // Check if email already exists
                    if (User::where('email', $row['email'])->exists()) {
                        $this->errors[] = "Baris {$rowNumber}: Email '{$row['email']}' sudah digunakan";
                        $this->failedCount++;
                        continue;
                    }

                    // Parse dates
                    $tanggalMasuk = $this->parseDate($row['tanggal_masuk_dd_mm_yyyy']);
                    $tanggalLahir = $this->parseDate($row['tanggal_lahir_dd_mm_yyyy']);

                    if (!$tanggalMasuk) {
                        $this->errors[] = "Baris {$rowNumber}: Format tanggal masuk tidak valid";
                        $this->failedCount++;
                        continue;
                    }

                    // Create user first
                    $user = User::create([
                        'name' => $row['nama_lengkap'],
                        'email' => $row['email'],
                        'password' => Hash::make('password123'), // Default password
                        'is_active' => $row['status_aktif_nonaktif_cuti_keluar'] === 'aktif'
                    ]);

                    // Assign role to user
                    DB::table('user_roles')->insert([
                        'user_id' => $user->id,
                        'role_id' => $role->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Create karyawan
                    Karyawan::create([
                        'user_id' => $user->id,
                        'nip' => $row['nip'],
                        'nama_lengkap' => $row['nama_lengkap'],
                        'email' => $row['email'],
                        'no_telepon' => $row['no_telepon'],
                        'department_id' => $department->id,
                        'jabatan_id' => $jabatan->id,
                        'tanggal_masuk' => $tanggalMasuk,
                        'gaji_pokok' => (int) str_replace(['Rp', '.', ',', ' '], '', $row['gaji_pokok']),
                        'status' => $row['status_aktif_nonaktif_cuti_keluar'],
                        'alamat' => $row['alamat'],
                        'tanggal_lahir' => $tanggalLahir,
                        'jenis_kelamin' => $row['jenis_kelamin_laki_laki_perempuan'],
                        'status_pernikahan' => $row['status_pernikahan_menikah_belum_menikah'],
                        'no_ktp' => $row['no_ktp']
                    ]);

                    $this->successCount++;
                } catch (\Exception $e) {
                    Log::error("Error importing karyawan row {$rowNumber}: " . $e->getMessage());
                    $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    $this->failedCount++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error in karyawan import: " . $e->getMessage());
            throw $e;
        }
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            // Try different date formats
            $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];

            foreach ($formats as $format) {
                $date = Carbon::createFromFormat($format, $dateString);
                if ($date) {
                    return $date->format('Y-m-d');
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string'],
            'jabatan' => ['required', 'string'],
            'tanggal_masuk_dd_mm_yyyy' => ['required'],
            'gaji_pokok' => ['required'],
            'status_aktif_nonaktif_cuti_keluar' => ['required', 'in:aktif,nonaktif,cuti,keluar'],
            'alamat' => ['nullable', 'string'],
            'tanggal_lahir_dd_mm_yyyy' => ['nullable'],
            'jenis_kelamin_laki_laki_perempuan' => ['nullable', 'in:laki-laki,perempuan'],
            'status_pernikahan_menikah_belum_menikah' => ['nullable', 'in:menikah,belum_menikah'],
            'no_ktp' => ['nullable', 'string', 'max:20'],
            'role_user_admin_manager_karyawan' => ['required', 'string']
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'department.required' => 'Department wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'tanggal_masuk_dd_mm_yyyy.required' => 'Tanggal masuk wajib diisi',
            'gaji_pokok.required' => 'Gaji pokok wajib diisi',
            'status_aktif_nonaktif_cuti_keluar.required' => 'Status wajib diisi',
            'status_aktif_nonaktif_cuti_keluar.in' => 'Status harus salah satu dari: aktif, nonaktif, cuti, keluar',
            'jenis_kelamin_laki_laki_perempuan.in' => 'Jenis kelamin harus laki-laki atau perempuan',
            'status_pernikahan_menikah_belum_menikah.in' => 'Status pernikahan harus menikah atau belum_menikah',
            'role_user_admin_manager_karyawan.required' => 'Role user wajib diisi'
        ];
    }

    public function getResults()
    {
        return [
            'success_count' => $this->successCount,
            'failed_count' => $this->failedCount,
            'errors' => $this->errors
        ];
    }
}
