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
        Schema::create('transaksi_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis', ['alokasi', 'penggunaan', 'pengembalian']);
            // alokasi = dana masuk ke project
            // penggunaan = project mengeluarkan dana untuk operasional
            // pengembalian = dana kembali dari project ke kas/bank
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan');
            $table->string('no_bukti')->nullable();

            // Sumber dana (dari mana dana berasal)
            $table->enum('sumber_dana_type', ['kas', 'bank'])->nullable();
            $table->foreignId('kas_id')->nullable()->constrained('kas')->onDelete('set null');
            $table->foreignId('rekening_bank_id')->nullable()->constrained('rekening_bank')->onDelete('set null');

            // Kategori pengeluaran project (untuk jenis 'penggunaan')
            $table->string('kategori_penggunaan')->nullable(); // 'material', 'tenaga_kerja', 'operasional', 'lainnya'

            // Relasi ke dokumen terkait
            $table->string('related_type')->nullable(); // Model yang terkait
            $table->unsignedBigInteger('related_id')->nullable(); // ID dokumen terkait

            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->index(['project_id', 'tanggal']);
            $table->index(['jenis', 'tanggal']);
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_projects');
    }
};
