<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonAnalysisToReturPenjualanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retur_penjualan_detail', function (Blueprint $table) {
            $table->text('reason_analysis')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retur_penjualan_detail', function (Blueprint $table) {
            $table->dropColumn('reason_analysis');
        });
    }
}