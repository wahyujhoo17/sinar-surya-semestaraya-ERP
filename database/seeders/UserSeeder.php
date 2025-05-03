<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sinar-surya.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Assign role admin ke user admin
        DB::table('user_roles')->insert([
            'user_id' => $admin->id,
            'role_id' => 1, // Role admin dengan ID 1
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tambahkan user lain jika diperlukan
        $users = [
            [
                'name' => 'Manager Penjualan',
                'email' => 'sales@sinar-surya.com',
                'password' => Hash::make('password'),
                'role_id' => 2 // Role sales dengan ID 2
            ],
            [
                'name' => 'Manager Gudang',
                'email' => 'warehouse@sinar-surya.com',
                'password' => Hash::make('password'),
                'role_id' => 3 // Role warehouse dengan ID 3
            ],
            [
                'name' => 'Manager Produksi',
                'email' => 'production@sinar-surya.com',
                'password' => Hash::make('password'),
                'role_id' => 4 // Role production dengan ID 4
            ]
        ];

        foreach ($users as $userData) {
            $roleId = $userData['role_id'];
            unset($userData['role_id']);

            $user = User::create(array_merge($userData, ['is_active' => true]));

            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
