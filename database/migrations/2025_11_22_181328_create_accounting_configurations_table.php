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
        Schema::create('accounting_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // pembayaran_hutang, penggajian, penjualan, dll
            $table->string('account_key'); // hutang_usaha, kas, bank, beban_gaji, dll
            $table->string('account_name'); // Nama akun untuk display
            $table->unsignedBigInteger('akun_id')->nullable(); // ID akun akuntansi
            $table->boolean('is_required')->default(true); // Apakah wajib dikonfigurasi
            $table->text('description')->nullable(); // Penjelasan penggunaan
            $table->timestamps();

            $table->unique(['transaction_type', 'account_key']); // Pastikan tidak duplikat
            $table->foreign('akun_id')->references('id')->on('akun_akuntansi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_configurations');
    }
};
