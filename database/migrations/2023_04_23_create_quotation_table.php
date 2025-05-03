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
        Schema::create('quotation', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['draft', 'dikirim', 'disetujui', 'ditolak', 'kedaluwarsa'])->default('draft');
            $table->date('tanggal_berlaku');
            $table->text('catatan')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->timestamps();
        });

        Schema::create('quotation_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotation')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->text('deskripsi')->nullable();
            $table->decimal('quantity', 15, 2);
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
        Schema::dropIfExists('quotation_detail');
        Schema::dropIfExists('quotation');
    }
};