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
        Schema::create('transfer_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('gudang_asal_id')->constrained('gudang');
            $table->foreignId('gudang_tujuan_id')->constrained('gudang');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['draft', 'diproses', 'selesai'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('transfer_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('transfer_barang')->onDelete('cascade');
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
        Schema::dropIfExists('transfer_barang_detail');
        Schema::dropIfExists('transfer_barang');
    }
};