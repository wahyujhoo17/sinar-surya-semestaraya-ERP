<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyimpan jenis diskon global Sales Order yang dipilih pengguna (toggle % / Rp),
 * agar nominal Rupiah yang diketik tidak lagi ditebak dari diskon_persen (2 desimal).
 *
 * Kolom nullable: SO lama bernilai NULL dan jenisnya tetap ditebak dari nilai yang tersimpan.
 *
 * Jalankan HANYA file ini (masih ada migration lama berstatus pending yang tabelnya sudah ada):
 * php artisan migrate --path=database/migrations/2026_11_17_000000_add_diskon_tipe_to_sales_order_table.php
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('sales_order', 'diskon_tipe')) {
            return;
        }

        Schema::table('sales_order', function (Blueprint $table) {
            $table->string('diskon_tipe', 10)
                ->nullable()
                ->after('diskon_nominal')
                ->comment('Jenis diskon global: persen / nominal. NULL = data lama (jenis ditebak dari nilai)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('sales_order', 'diskon_tipe')) {
            return;
        }

        Schema::table('sales_order', function (Blueprint $table) {
            $table->dropColumn('diskon_tipe');
        });
    }
};
