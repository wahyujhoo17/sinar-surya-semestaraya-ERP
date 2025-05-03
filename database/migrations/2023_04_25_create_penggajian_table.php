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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('total_gaji', 15, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['draft', 'disetujui', 'dibayar'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->timestamps();
            
            // Setiap karyawan hanya bisa memiliki 1 penggajian per bulan
            $table->unique(['karyawan_id', 'bulan', 'tahun']);
        });
        
        Schema::create('komponen_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penggajian_id')->constrained('penggajian')->onDelete('cascade');
            $table->string('nama_komponen');
            $table->enum('jenis', ['pendapatan', 'potongan']);
            $table->decimal('nilai', 15, 2);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_gaji');
        Schema::dropIfExists('penggajian');
    }
};