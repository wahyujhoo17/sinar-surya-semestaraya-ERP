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
        Schema::table('karyawan', function (Blueprint $table) {
            // Tunjangan (Pendapatan)
            $table->decimal('tunjangan_btn', 15, 2)->default(0)->after('gaji_pokok');
            $table->decimal('tunjangan_keluarga', 15, 2)->default(0)->after('tunjangan_btn');
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0)->after('tunjangan_keluarga');
            $table->decimal('tunjangan_transport', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('tunjangan_makan', 15, 2)->default(0)->after('tunjangan_transport');
            $table->decimal('tunjangan_pulsa', 15, 2)->default(0)->after('tunjangan_makan');

            // Default nilai untuk tunjangan yang umum
            $table->decimal('default_tunjangan', 15, 2)->default(0)->after('tunjangan_pulsa');
            $table->decimal('default_bonus', 15, 2)->default(0)->after('default_tunjangan');
            $table->decimal('default_lembur_rate', 15, 2)->default(0)->after('default_bonus'); // rate per jam

            // Potongan/Pengurangan (Default)
            $table->decimal('bpjs', 15, 2)->default(0)->after('default_lembur_rate');
            $table->decimal('default_potongan', 15, 2)->default(0)->after('bpjs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn([
                'tunjangan_btn',
                'tunjangan_keluarga',
                'tunjangan_jabatan',
                'tunjangan_transport',
                'tunjangan_makan',
                'tunjangan_pulsa',
                'default_tunjangan',
                'default_bonus',
                'default_lembur_rate',
                'bpjs',
                'default_potongan'
            ]);
        });
    }
};
