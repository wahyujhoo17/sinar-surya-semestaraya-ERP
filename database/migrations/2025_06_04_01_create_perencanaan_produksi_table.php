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
        Schema::create('perencanaan_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('sales_order_id')->constrained('sales_order');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'berjalan', 'selesai', 'dibatalkan'])
                ->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perencanaan_produksi');
    }
};
