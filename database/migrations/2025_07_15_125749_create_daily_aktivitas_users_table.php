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
        Schema::create('daily_aktivitas_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_aktivitas_id')->constrained('daily_aktivitas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['assigned', 'watcher', 'collaborator'])->default('assigned');
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['daily_aktivitas_id', 'user_id']);

            // Indexes for better performance
            $table->index(['daily_aktivitas_id', 'role']);
            $table->index(['user_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_aktivitas_users');
    }
};
