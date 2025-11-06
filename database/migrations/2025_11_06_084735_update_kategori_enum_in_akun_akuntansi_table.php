<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom kategori dengan menambahkan nilai enum baru
        DB::statement("ALTER TABLE akun_akuntansi MODIFY COLUMN kategori ENUM('asset', 'liability', 'equity', 'income', 'expense', 'purchase', 'other_income', 'other_expense', 'other') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke nilai enum sebelumnya
        DB::statement("ALTER TABLE akun_akuntansi MODIFY COLUMN kategori ENUM('asset', 'liability', 'equity', 'income', 'expense', 'other') NOT NULL");
    }
};
