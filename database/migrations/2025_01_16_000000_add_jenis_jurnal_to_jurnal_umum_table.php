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
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->enum('jenis_jurnal', ['umum', 'penyesuaian', 'penutup', 'koreksi'])
                ->default('umum')
                ->after('keterangan')
                ->comment('Jenis jurnal: umum, penyesuaian, penutup, koreksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropColumn('jenis_jurnal');
        });
    }
};
