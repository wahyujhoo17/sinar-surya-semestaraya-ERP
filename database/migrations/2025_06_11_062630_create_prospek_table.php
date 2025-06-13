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
        Schema::create('prospek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prospek');
            $table->string('perusahaan')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('baru');
            $table->string('sumber')->nullable();
            $table->decimal('nilai_potensi', 15, 2)->nullable();
            $table->date('tanggal_kontak')->nullable();
            $table->date('tanggal_followup')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customer')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospek');
    }
};
