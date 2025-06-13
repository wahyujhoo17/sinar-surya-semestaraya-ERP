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
        Schema::create('prospek_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospek_id')->constrained('prospek')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipe');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal');
            $table->string('hasil')->nullable();
            $table->boolean('perlu_followup')->default(false);
            $table->dateTime('tanggal_followup')->nullable();
            $table->string('status_followup')->nullable();
            $table->text('catatan_followup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospek_aktivitas');
    }
};
