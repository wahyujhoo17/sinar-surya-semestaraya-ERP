<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Bundle code: BDL-001
            $table->string('nama'); // Bundle name
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_bundle', 15, 2); // Bundle price (can be different from sum of individual prices)
            $table->decimal('harga_normal', 15, 2)->nullable(); // Normal price if bought separately
            $table->decimal('diskon_persen', 5, 2)->default(0); // Discount percentage
            $table->boolean('is_active')->default(true);
            $table->string('gambar')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_produk')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_bundles');
    }
};
