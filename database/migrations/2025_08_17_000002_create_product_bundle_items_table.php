<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('product_bundles')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->decimal('quantity', 10, 2); // Quantity of this product in the bundle
            $table->decimal('harga_satuan', 15, 2)->nullable(); // Individual price in bundle (can override)
            $table->timestamps();

            $table->unique(['bundle_id', 'produk_id']); // One product can't be duplicated in same bundle
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_bundle_items');
    }
};
