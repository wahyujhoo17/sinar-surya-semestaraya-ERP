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
        Schema::create('pembayaran_piutang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoice')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nomor_referensi')->nullable();
            $table->string('metode_pembayaran');
            $table->text('keterangan')->nullable();
            $table->decimal('jumlah', 15, 2);
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
