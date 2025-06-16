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
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_referensi', 50);
            $table->foreignId('akun_id')->constrained('akun_akuntansi');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('ref_id')->nullable();
            $table->string('ref_type')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('tanggal');
            $table->index('no_referensi');
            $table->index(['tanggal', 'no_referensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
