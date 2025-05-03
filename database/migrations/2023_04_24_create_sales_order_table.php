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
        Schema::create('sales_order', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('quotation_id')->nullable()->constrained('quotation');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->enum('status_pengiriman', ['belum_dikirim', 'sebagian', 'dikirim'])->default('belum_dikirim');
            $table->date('tanggal_kirim')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->text('catatan')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_order_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_order')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->text('deskripsi')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->decimal('quantity_terkirim', 15, 2)->default(0);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->decimal('harga', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_detail');
        Schema::dropIfExists('sales_order');
    }
};