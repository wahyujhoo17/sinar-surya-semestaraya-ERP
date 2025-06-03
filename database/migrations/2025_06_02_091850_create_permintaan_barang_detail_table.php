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
        Schema::create('permintaan_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_barang_id')->constrained('permintaan_barang')->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('set null');
            $table->foreignId('sales_order_detail_id')->nullable()->constrained('sales_order_detail')->onDelete('set null');
            $table->foreignId('satuan_id')->nullable()->constrained('satuan')->onDelete('set null');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->decimal('jumlah_tersedia', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_barang_detail');
    }
};
