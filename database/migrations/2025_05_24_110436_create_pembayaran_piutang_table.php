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
        Schema::dropIfExists('pembayaran_piutang');

        Schema::create('pembayaran_piutang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('invoice_id')->constrained('invoice');
            $table->foreignId('customer_id')->constrained('customer');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_pembayaran');
            $table->string('no_referensi')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang');
    }
};
