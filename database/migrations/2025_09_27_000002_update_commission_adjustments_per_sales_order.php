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
            // Drop kolom lama yang untuk global
            $table->dropColumn([
                'cashback_persen',
                'overhead_persen',
                'netto_penjualan_original',
                'netto_beli_original',
                'netto_penjualan_adjusted',
                'netto_beli_adjusted'
            ]);

            // Tambah kolom untuk menyimpan sales order id
            $table->unsignedBigInteger('sales_order_id')->nullable()->after('keterangan')->comment('ID sales order untuk komisi ini');

            // Tambah kolom untuk penyesuaian per sales order
            $table->decimal('cashback_nominal', 15, 2)->default(0)->after('sales_order_id')->comment('Nominal cashback yang mengurangi netto penjualan');
            $table->decimal('overhead_persen', 5, 2)->default(0)->after('cashback_nominal')->comment('Persentase overhead yang menambah harga beli');

            // Tambah kolom untuk menyimpan detail perhitungan
            $table->decimal('netto_penjualan_original', 15, 2)->nullable()->after('overhead_persen')->comment('Netto penjualan sebelum penyesuaian');
            $table->decimal('netto_beli_original', 15, 2)->nullable()->after('netto_penjualan_original')->comment('Netto beli sebelum penyesuaian');
            $table->decimal('netto_penjualan_adjusted', 15, 2)->nullable()->after('netto_beli_original')->comment('Netto penjualan setelah dikurangi cashback');
            $table->decimal('netto_beli_adjusted', 15, 2)->nullable()->after('netto_penjualan_adjusted')->comment('Netto beli setelah ditambah overhead');
            $table->decimal('margin_persen', 8, 4)->nullable()->after('netto_beli_adjusted')->comment('Margin persentase yang digunakan untuk perhitungan');
            $table->decimal('komisi_rate', 5, 2)->nullable()->after('margin_persen')->comment('Rate komisi yang digunakan');

            // Add foreign key constraint
            $table->foreign('sales_order_id')->references('id')->on('sales_order')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_gaji', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['sales_order_id']);

            // Drop new columns
            $table->dropColumn([
                'sales_order_id',
                'cashback_nominal',
                'overhead_persen',
                'netto_penjualan_original',
                'netto_beli_original',
                'netto_penjualan_adjusted',
                'netto_beli_adjusted',
                'margin_persen',
                'komisi_rate'
            ]);

            // Add back old columns
            $table->decimal('cashback_persen', 5, 2)->default(0)->after('keterangan');
            $table->decimal('overhead_persen', 5, 2)->default(0)->after('cashback_persen');
            $table->decimal('netto_penjualan_original', 15, 2)->nullable()->after('overhead_persen');
            $table->decimal('netto_beli_original', 15, 2)->nullable()->after('netto_penjualan_original');
            $table->decimal('netto_penjualan_adjusted', 15, 2)->nullable()->after('netto_beli_original');
            $table->decimal('netto_beli_adjusted', 15, 2)->nullable()->after('netto_penjualan_adjusted');
        });
    }
};
