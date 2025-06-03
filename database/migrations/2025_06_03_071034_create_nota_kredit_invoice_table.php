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
        Schema::create('nota_kredit_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_kredit_id')->constrained('nota_kredit')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoice')->onDelete('cascade');
            $table->decimal('applied_amount', 15, 2)->default(0);
            $table->timestamps();

            // Prevent duplicate applications
            $table->unique(['nota_kredit_id', 'invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_kredit_invoice');
    }
};