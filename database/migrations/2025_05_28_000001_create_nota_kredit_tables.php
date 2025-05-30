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
        Schema::create('nota_kredit', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('retur_penjualan_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status')->default('draft'); // draft, diproses, selesai
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('retur_penjualan_id')->references('id')->on('retur_penjualan')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade');
            $table->foreign('sales_order_id')->references('id')->on('sales_order')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('nota_kredit_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nota_kredit_id');
            $table->unsignedBigInteger('produk_id');
            $table->decimal('quantity', 15, 2);
            $table->unsignedBigInteger('satuan_id');
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('nota_kredit_id')->references('id')->on('nota_kredit')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
        });

        // Add relationship field to retur_penjualan
        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('nota_kredit_id')->nullable()->after('user_id');
            $table->foreign('nota_kredit_id')->references('id')->on('nota_kredit')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key from retur_penjualan first
        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->dropForeign(['nota_kredit_id']);
            $table->dropColumn('nota_kredit_id');
        });

        Schema::dropIfExists('nota_kredit_detail');
        Schema::dropIfExists('nota_kredit');
    }
};
