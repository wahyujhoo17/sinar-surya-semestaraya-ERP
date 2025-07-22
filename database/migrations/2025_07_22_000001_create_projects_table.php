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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // PROJ-2025-001
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('saldo', 15, 2)->default(0); // Saldo aktual project
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'aktif', 'selesai', 'ditunda'])->default('draft');
            $table->foreignId('customer_id')->nullable()->constrained('customer')->onDelete('set null');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_order')->onDelete('set null');
            $table->string('pic_internal')->nullable(); // Person in charge internal
            $table->string('pic_customer')->nullable(); // Contact person customer
            $table->json('metadata')->nullable(); // Data tambahan seperti lokasi, timeline, dll
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index(['status', 'is_aktif']);
            $table->index('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
