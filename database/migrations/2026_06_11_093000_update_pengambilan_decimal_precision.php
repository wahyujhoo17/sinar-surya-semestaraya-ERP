<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengambilan_bahan_baku_detail', function (Blueprint $table) {
            $table->decimal('jumlah_diminta', 15, 4)->change();
            $table->decimal('jumlah_diambil', 15, 4)->change();
        });

        Schema::table('stok_produk', function (Blueprint $table) {
            $table->decimal('jumlah', 15, 4)->change();
        });

        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->decimal('jumlah_sebelum', 15, 4)->change();
            $table->decimal('jumlah_perubahan', 15, 4)->change();
            $table->decimal('jumlah_setelah', 15, 4)->change();
        });

        Schema::table('work_order_materials', function (Blueprint $table) {
            $table->decimal('quantity', 15, 4)->change();
            $table->decimal('quantity_terpakai', 15, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengambilan_bahan_baku_detail', function (Blueprint $table) {
            $table->decimal('jumlah_diminta', 15, 2)->change();
            $table->decimal('jumlah_diambil', 15, 2)->change();
        });

        Schema::table('stok_produk', function (Blueprint $table) {
            $table->decimal('jumlah', 15, 2)->change();
        });

        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->decimal('jumlah_sebelum', 15, 2)->change();
            $table->decimal('jumlah_perubahan', 15, 2)->change();
            $table->decimal('jumlah_setelah', 15, 2)->change();
        });

        Schema::table('work_order_materials', function (Blueprint $table) {
            $table->decimal('quantity', 15, 2)->change();
            $table->decimal('quantity_terpakai', 15, 2)->change();
        });
    }
};
