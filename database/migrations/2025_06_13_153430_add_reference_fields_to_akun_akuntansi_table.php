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
        Schema::table('akun_akuntansi', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_id')->nullable()->after('is_active');
            $table->string('ref_type')->nullable()->after('ref_id');
            $table->index(['ref_id', 'ref_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun_akuntansi', function (Blueprint $table) {
            $table->dropIndex(['ref_id', 'ref_type']);
            $table->dropColumn('ref_id');
            $table->dropColumn('ref_type');
        });
    }
};
