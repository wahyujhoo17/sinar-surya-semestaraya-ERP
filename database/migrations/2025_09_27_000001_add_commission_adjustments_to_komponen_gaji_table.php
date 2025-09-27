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
        Schema::table('komponen_gaji', function (Blueprint $table) {
            // Menambahkan kolom untuk penyesuaian komisi
            $table->decimal('cashback_persen', 5, 2)->default(0)->after('keterangan')->comment('Persentase cashback yang mengurangi netto penjualan');
            $table->decimal('overhead_persen', 5, 2)->default(0)->after('cashback_persen')->comment('Persentase overhead yang menambah harga beli');
            $table->decimal('netto_penjualan_original', 15, 2)->nullable()->after('overhead_persen')->comment('Netto penjualan sebelum dikurangi cashback');
            $table->decimal('netto_beli_original', 15, 2)->nullable()->after('netto_penjualan_original')->comment('Netto beli sebelum ditambah overhead');
            $table->decimal('netto_penjualan_adjusted', 15, 2)->nullable()->after('netto_beli_original')->comment('Netto penjualan setelah dikurangi cashback');
            $table->decimal('netto_beli_adjusted', 15, 2)->nullable()->after('netto_penjualan_adjusted')->comment('Netto beli setelah ditambah overhead');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_gaji', function (Blueprint $table) {
            $table->dropColumn([
                'cashback_persen',
                'overhead_persen',
                'netto_penjualan_original',
                'netto_beli_original',
                'netto_penjualan_adjusted',
                'netto_beli_adjusted'
            ]);
        });
    }
};
