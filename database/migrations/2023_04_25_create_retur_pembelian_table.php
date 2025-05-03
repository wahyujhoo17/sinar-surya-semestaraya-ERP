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
        Schema::create('retur_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('purchase_order_id')->constrained('purchase_order');
            $table->foreignId('supplier_id')->constrained('supplier');
            $table->foreignId('user_id')->constrained('users');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'diproses', 'selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('retur_pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id')->constrained('retur_pembelian')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('quantity', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->string('alasan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelian_detail');
        Schema::dropIfExists('retur_pembelian');
    }
};