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
        Schema::create('laporan_pajaks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pajak'); // ppn_keluaran, ppn_masukan, pph21, pph23, pph4_ayat2
            $table->string('no_faktur_pajak')->nullable();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->date('tanggal_faktur')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('periode')->nullable(); // for monthly reporting
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('dasar_pengenaan_pajak', 15, 2)->default(0);
            $table->decimal('tarif_pajak', 5, 2)->default(0); // percentage
            $table->decimal('jumlah_pajak', 15, 2)->default(0);
            $table->decimal('nilai', 15, 2)->default(0); // for backward compatibility
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar', 'lebih_bayar'])->default('belum_bayar');
            $table->string('npwp')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['jenis_pajak', 'status']);
            $table->index(['periode_awal', 'periode_akhir']);
            $table->index(['tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pajaks');
    }
};
