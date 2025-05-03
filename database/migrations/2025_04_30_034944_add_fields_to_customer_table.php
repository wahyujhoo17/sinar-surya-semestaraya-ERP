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
        Schema::table('customer', function (Blueprint $table) {
            $table->string('tipe')->nullable()->after('nama');
            $table->string('jalan')->nullable()->after('tipe');
            $table->string('kota')->nullable()->after('jalan');
            $table->string('provinsi')->nullable()->after('kota');
            $table->string('kode_pos')->nullable()->after('provinsi');
            $table->string('negara')->nullable()->after('kode_pos');
            $table->string('company')->nullable()->after('negara');
            $table->string('group')->nullable()->after('company');
            $table->string('industri')->nullable()->after('group');
            $table->string('sales_name')->nullable()->after('industri');
            $table->dropColumn(['npwp', 'jabatan_kontak']); // hapus kolom lama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn([
                'tipe',
                'jalan',
                'kota',
                'provinsi',
                'kode_pos',
                'negara',
                'company',
                'group',
                'industri',
                'sales_name'
            ]);
        });
    }
};