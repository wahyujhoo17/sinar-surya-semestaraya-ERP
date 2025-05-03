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
        Schema::create('work_order', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('bom_id')->constrained('bill_of_materials');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_order');
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('gudang_produksi_id')->constrained('gudang');
            $table->foreignId('gudang_hasil_id')->constrained('gudang');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('quantity', 15, 2);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('deadline')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['direncanakan', 'berjalan', 'selesai', 'dibatalkan'])->default('direncanakan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('work_order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_order')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->decimal('quantity', 15, 2);
            $table->decimal('quantity_terpakai', 15, 2)->default(0);
            $table->foreignId('satuan_id')->constrained('satuan');
            $table->boolean('consumed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_materials');
        Schema::dropIfExists('work_order');
    }
};