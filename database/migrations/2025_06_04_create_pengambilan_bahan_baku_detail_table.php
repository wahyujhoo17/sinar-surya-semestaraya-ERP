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
        Schema::create('pengambilan_bahan_baku_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengambilan_bahan_baku_id')->constrained('pengambilan_bahan_baku')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('jumlah_diminta', 15, 2);
            $table->decimal('jumlah_diambil', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->string('batch_number')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan_bahan_baku_detail');
    }
};
