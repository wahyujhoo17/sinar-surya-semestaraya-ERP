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
        Schema::create('uang_muka_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_order');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('jumlah_tersedia', 15, 2); // sisa yang belum diaplikasikan
            $table->string('metode_pembayaran')->default('kas'); // kas atau bank
            $table->foreignId('kas_id')->nullable()->constrained('kas')->nullOnDelete();
            $table->foreignId('rekening_bank_id')->nullable()->constrained('rekening_bank')->nullOnDelete();
            $table->string('nomor_referensi')->nullable(); // nomor transfer/cek
            $table->enum('status', ['pending', 'confirmed', 'applied', 'partially_applied'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('uang_muka_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uang_muka_penjualan_id')->constrained('uang_muka_penjualan');
            $table->foreignId('invoice_id')->constrained('invoice');
            $table->decimal('jumlah_aplikasi', 15, 2);
            $table->date('tanggal_aplikasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_muka_aplikasi');
        Schema::dropIfExists('uang_muka_penjualan');
    }
};
