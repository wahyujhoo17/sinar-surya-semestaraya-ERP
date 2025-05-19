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
        Schema::create('pengembalian_dana', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('purchase_order_id')->constrained('purchase_order')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('restrict');
            $table->enum('metode_penerimaan', ['kas', 'bank']);
            $table->foreignId('kas_id')->nullable()->constrained('kas')->onDelete('restrict');
            $table->foreignId('rekening_id')->nullable()->constrained('rekening_bank')->onDelete('restrict');
            $table->string('no_referensi')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_dana');
    }
};
