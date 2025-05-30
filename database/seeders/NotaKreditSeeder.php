<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\NotaKredit;
use App\Models\NotaKreditDetail;
use App\Models\Produk;
use App\Models\ReturPenjualan;
use App\Models\SalesOrder;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotaKreditSeeder extends Seeder
{
    /**
     * Seed the NotaKredit data for testing.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get necessary reference data
            $returPenjualan = ReturPenjualan::where('status', 'disetujui')
                ->orWhere('status', 'selesai')
                ->take(3)
                ->get();

            if ($returPenjualan->isEmpty()) {
                Log::warning('No approved returns found for NotaKreditSeeder. Seeding aborted.');
                return;
            }

            $adminUser = User::where('email', 'admin@example.com')->first();
            if (!$adminUser) {
                Log::warning('Admin user not found for NotaKreditSeeder. Using first available user.');
                $adminUser = User::first();

                if (!$adminUser) {
                    Log::error('No users found for NotaKreditSeeder. Seeding aborted.');
                    return;
                }
            }

            $satuan = Satuan::first();
            if (!$satuan) {
                Log::warning('No units found for NotaKreditSeeder. Seeding aborted.');
                return;
            }

            // Create credit notes for approved returns
            foreach ($returPenjualan as $index => $retur) {
                // Get return details to create credit note details
                $returDetails = $retur->details;
                if ($returDetails->isEmpty()) {
                    Log::warning("Return {$retur->nomor} has no details. Skipping.");
                    continue;
                }

                // Create credit note
                $notaKredit = NotaKredit::create([
                    'nomor' => 'NK-' . date('Ymd') . '-' . rand(1000, 9999),
                    'tanggal' => now(),
                    'retur_penjualan_id' => $retur->id,
                    'customer_id' => $retur->customer_id,
                    'sales_order_id' => $retur->sales_order_id,
                    'user_id' => $adminUser->id,
                    'total' => 0, // Will calculate after adding details
                    'status' => $index % 2 == 0 ? 'draft' : 'selesai',
                    'catatan' => 'Nota kredit yang dibuat oleh seeder untuk pengujian'
                ]);

                // Create credit note details based on return details
                $total = 0;
                foreach ($returDetails as $returDetail) {
                    $produk = $returDetail->produk;
                    if (!$produk) {
                        Log::warning("Product not found for return detail. Skipping.");
                        continue;
                    }

                    $harga = $produk->harga_jual ?? rand(10000, 100000);
                    $quantity = $returDetail->quantity;
                    $subtotal = $harga * $quantity;

                    // Create credit note detail
                    NotaKreditDetail::create([
                        'nota_kredit_id' => $notaKredit->id,
                        'produk_id' => $produk->id,
                        'quantity' => $quantity,
                        'satuan_id' => $returDetail->satuan_id,
                        'harga' => $harga,
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                }

                // Update the total
                $notaKredit->update(['total' => $total]);

                // If the credit note status is 'selesai', update the return status as well
                if ($notaKredit->status == 'selesai' && $retur->status != 'selesai') {
                    $retur->update(['status' => 'selesai']);
                }
            }

            DB::commit();

            Log::info('NotaKreditSeeder completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in NotaKreditSeeder: ' . $e->getMessage());
            throw $e;
        }
    }
}
