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
        Schema::create('retur_penjualan_pengganti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id')->constrained('retur_penjualan', 'id')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk', 'id')->onDelete('restrict');
            $table->foreignId('gudang_id')->constrained('gudang', 'id')->onDelete('restrict');
            $table->foreignId('satuan_id')->constrained('satuan', 'id')->onDelete('restrict');
            $table->decimal('quantity', 12, 2);
            $table->date('tanggal_pengiriman');
            $table->string('no_referensi')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_penjualan_pengganti');
    }
};
