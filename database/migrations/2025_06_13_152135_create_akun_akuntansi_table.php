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
        Schema::create('akun_akuntansi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('kategori', ['asset', 'liability', 'equity', 'income', 'expense', 'other']);
            $table->enum('tipe', ['header', 'detail']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('akun_akuntansi', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('akun_akuntansi')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_akuntansi');
    }
};
