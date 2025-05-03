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
        Schema::create('delivery_order', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('sales_order_id')->constrained('sales_order');
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('status', ['draft', 'dikirim', 'diterima', 'dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('keterangan_penerima')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_order_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('delivery_order')->onDelete('cascade');
            $table->foreignId('sales_order_detail_id')->constrained('sales_order_detail');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('quantity', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_order_detail');
        Schema::dropIfExists('delivery_order');
    }
};