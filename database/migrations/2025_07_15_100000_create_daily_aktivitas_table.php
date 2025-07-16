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
        Schema::create('daily_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_aktivitas', [
                'meeting',
                'call',
                'email',
                'task',
                'follow_up',
                'presentasi',
                'kunjungan',
                'training',
                'lainnya'
            ])->default('task');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
            $table->enum('status', ['pending', 'dalam_proses', 'selesai', 'dibatalkan'])->default('pending');
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_selesai')->nullable();
            $table->datetime('deadline')->nullable();
            $table->text('catatan')->nullable();
            $table->text('hasil')->nullable();
            $table->json('attachments')->nullable(); // untuk menyimpan file attachments
            $table->string('lokasi')->nullable();
            $table->text('peserta')->nullable(); // participant emails/names
            $table->decimal('estimasi_durasi', 5, 2)->nullable(); // dalam jam
            $table->decimal('durasi_aktual', 5, 2)->nullable(); // dalam jam
            $table->boolean('reminder_sent')->default(false);
            $table->datetime('reminder_at')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('related_model')->nullable(); // prospek, customer, etc
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['tanggal_mulai', 'status']);
            $table->index(['prioritas', 'status']);
            $table->index(['related_model', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_aktivitas');
    }
};
