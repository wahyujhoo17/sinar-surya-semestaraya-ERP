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
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('po_id')->nullable()->constrained('purchase_order');
            $table->foreignId('supplier_id')->constrained('supplier');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->string('nomor_surat_jalan')->nullable();
            $table->date('tanggal_surat_jalan')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('penerimaan_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan_barang')->onDelete('cascade');
            $table->foreignId('po_detail_id')->nullable()->constrained('purchase_order_detail');
            $table->foreignId('produk_id')->nullable()->constrained('produk');
            $table->string('nama_item')->nullable(); // Untuk item yang tidak ada di master produk
            $table->text('deskripsi')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->string('batch_number')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang_detail');
        Schema::dropIfExists('penerimaan_barang');
    }
};