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
        Schema::create('periode_akuntansi', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama periode, contoh: 2025-01');
            $table->date('tanggal_mulai')->comment('Tanggal mulai periode');
            $table->date('tanggal_akhir')->comment('Tanggal akhir periode');
            $table->enum('status', ['open', 'closed', 'locked'])->default('open')
                ->comment('Status: open=buka, closed=tutup, locked=kunci');
            $table->date('tanggal_tutup')->nullable()->comment('Tanggal penutupan periode');
            $table->foreignId('closed_by')->nullable()->constrained('users')
                ->comment('User yang menutup periode');
            $table->text('catatan_penutupan')->nullable()
                ->comment('Catatan saat penutupan periode');
            $table->boolean('is_year_end')->default(false)
                ->comment('Apakah periode akhir tahun');
            $table->timestamps();

            // Index
            $table->unique(['tanggal_mulai', 'tanggal_akhir']);
            $table->index('status');
            $table->index(['tanggal_mulai', 'tanggal_akhir', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_akuntansi');
    }
};
