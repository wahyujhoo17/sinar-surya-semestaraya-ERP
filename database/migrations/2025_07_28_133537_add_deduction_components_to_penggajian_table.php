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
        Schema::table('penggajian', function (Blueprint $table) {
            // Menambahkan field komisi setelah potongan
            $table->decimal('komisi', 15, 2)->default(0)->after('potongan');

            // Menambahkan komponen potongan spesifik
            $table->decimal('cash_bon', 15, 2)->default(0)->after('komisi');
            $table->decimal('keterlambatan', 15, 2)->default(0)->after('cash_bon');
            $table->decimal('bpjs_karyawan', 15, 2)->default(0)->after('keterlambatan');

            // Field untuk THP (Take Home Pay)
            $table->decimal('thp', 15, 2)->default(0)->after('total_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn([
                'komisi',
                'cash_bon',
                'keterlambatan',
                'bpjs_karyawan',
                'thp'
            ]);
        });
    }
};
