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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('product_sku')->nullable();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategori_produk');
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_produk');
            $table->string('ukuran')->nullable();
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->string('merek')->nullable();
            $table->string('sub_kategori')->nullable(); // For "Bahan Jadi" or "Bahan Baku"
            $table->string('type_material')->nullable();
            $table->string('kualitas')->nullable();
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('stok_minimum', 15, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add index for common search fields
            $table->index('product_sku');
            $table->index('nama');
            $table->index('merek');
            $table->index('sub_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};