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
        Schema::create('perencanaan_produksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perencanaan_produksi_id')->constrained('perencanaan_produksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('jumlah', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->decimal('stok_tersedia', 15, 2)->default(0);
            $table->decimal('jumlah_produksi', 15, 2);
            $table->decimal('jumlah_selesai', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perencanaan_produksi_detail');
    }
};
