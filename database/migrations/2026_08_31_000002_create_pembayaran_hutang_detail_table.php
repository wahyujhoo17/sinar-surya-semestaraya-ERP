<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran_hutang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_hutang_id')->constrained('pembayaran_hutang')->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained('purchase_order')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Make purchase_order_id in pembayaran_hutang nullable for multi-PO payments
        Schema::table('pembayaran_hutang', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_id')->nullable()->change();
        });

        // Backfill existing records
        $existingPayments = DB::table('pembayaran_hutang')->whereNotNull('purchase_order_id')->get();
        foreach ($existingPayments as $payment) {
            // Verify that purchase order exists
            $poExists = DB::table('purchase_order')->where('id', $payment->purchase_order_id)->exists();
            if ($poExists) {
                DB::table('pembayaran_hutang_detail')->insert([
                    'pembayaran_hutang_id' => $payment->id,
                    'purchase_order_id' => $payment->purchase_order_id,
                    'jumlah' => $payment->jumlah,
                    'catatan' => $payment->catatan,
                    'created_at' => $payment->created_at ?? now(),
                    'updated_at' => $payment->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang_detail');
    }
};
