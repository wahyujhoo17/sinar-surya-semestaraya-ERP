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
        Schema::create('quality_control', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('work_order_id')->constrained('work_order');
            $table->enum('status', ['sebagian_gagal', 'semua_lolos'])->default(value: 'semua_lolos');
            $table->foreignId('inspector_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_control');
    }
};
