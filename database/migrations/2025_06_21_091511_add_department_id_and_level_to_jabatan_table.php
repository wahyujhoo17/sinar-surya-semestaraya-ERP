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
        Schema::table('jabatan', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('deskripsi')->constrained('department')->nullOnDelete();
            $table->integer('level')->nullable()->after('department_id')->comment('Level jabatan: 1=Direktur, 2=Manager, 3=Supervisor, 4=Staff, 5=Operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'level']);
        });
    }
};
