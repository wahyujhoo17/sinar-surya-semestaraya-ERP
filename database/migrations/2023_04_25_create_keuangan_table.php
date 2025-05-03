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
        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('cabang')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->boolean('is_perusahaan')->default(true);
            $table->timestamps();
        });

        Schema::create('transaksi_bank', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('rekening_id')->constrained('rekening_bank');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();
            $table->string('no_referensi')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kas_id')->constrained('kas');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();
            $table->string('no_bukti')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas');
        Schema::dropIfExists('kas');
        Schema::dropIfExists('transaksi_bank');
        Schema::dropIfExists('rekening_bank');
    }
};