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
        Schema::table('invoice_detail', function (Blueprint $table) {
            // Drop foreign key constraint dulu
            $table->dropForeign(['produk_id']);
            // Ubah jadi nullable
            $table->unsignedBigInteger('produk_id')->nullable()->change();
            // Tambah kembali foreign key
            $table->foreign('produk_id')->references('id')->on('produk');

            // satuan_id juga nullable untuk bundle
            $table->dropForeign(['satuan_id']);
            $table->unsignedBigInteger('satuan_id')->nullable()->change();
            $table->foreign('satuan_id')->references('id')->on('satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->unsignedBigInteger('produk_id')->nullable(false)->change();
            $table->foreign('produk_id')->references('id')->on('produk');

            $table->dropForeign(['satuan_id']);
            $table->unsignedBigInteger('satuan_id')->nullable(false)->change();
            $table->foreign('satuan_id')->references('id')->on('satuan');
        });
    }
};
