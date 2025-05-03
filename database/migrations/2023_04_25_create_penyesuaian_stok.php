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
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->foreignId('user_id')->constrained('users');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'disetujui', 'selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('penyesuaian_stok_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyesuaian_id')->constrained('penyesuaian_stok')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('stok_tercatat', 15, 2);
            $table->decimal('stok_fisik', 15, 2);
            $table->decimal('selisih', 15, 2);
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
        Schema::dropIfExists('penyesuaian_stok_detail');
        Schema::dropIfExists('penyesuaian_stok');
    }
};