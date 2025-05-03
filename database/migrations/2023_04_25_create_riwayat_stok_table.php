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
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_id')->constrained('stok_produk');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('jumlah_sebelum', 15, 2);
            $table->decimal('jumlah_perubahan', 15, 2);
            $table->decimal('jumlah_setelah', 15, 2);
            $table->enum('jenis', ['masuk', 'keluar', 'penyesuaian', 'transfer']);
            $table->string('referensi_tipe')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};