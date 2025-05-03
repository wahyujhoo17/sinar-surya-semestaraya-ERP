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
        Schema::create('gudang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->unsignedBigInteger('penanggung_jawab')->nullable();
            $table->enum('jenis', ['utama', 'cabang', 'produksi']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('penanggung_jawab')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang');
    }
};