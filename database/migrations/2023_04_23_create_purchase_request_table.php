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
        Schema::create('purchase_request', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'selesai'])->default('draft');
            $table->timestamps();
            
            // Constraint akan dibuat setelah table department ada
            // $table->foreign('department_id')->references('id')->on('department');
        });

        Schema::create('purchase_request_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_id')->constrained('purchase_request')->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained('produk');
            $table->string('nama_item')->nullable(); // Untuk item yang tidak ada di master produk
            $table->text('deskripsi')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->decimal('harga_estimasi', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_detail');
        Schema::dropIfExists('purchase_request');
    }
};