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
        Schema::create('pembayaran_piutang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_piutang_id')->constrained('pembayaran_piutang')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoice')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Make invoice_id in pembayaran_piutang nullable for multi-invoice payments
        Schema::table('pembayaran_piutang', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
        });

        // Backfill existing records
        $existingPayments = DB::table('pembayaran_piutang')->whereNotNull('invoice_id')->get();
        foreach ($existingPayments as $payment) {
            // Verify that invoice exists
            $invoiceExists = DB::table('invoice')->where('id', $payment->invoice_id)->exists();
            if ($invoiceExists) {
                DB::table('pembayaran_piutang_detail')->insert([
                    'pembayaran_piutang_id' => $payment->id,
                    'invoice_id' => $payment->invoice_id,
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
        Schema::dropIfExists('pembayaran_piutang_detail');
    }
};
