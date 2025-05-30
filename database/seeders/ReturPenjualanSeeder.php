<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Produk;
use App\Models\ReturPenjualan;
use App\Models\ReturPenjualanDetail;
use App\Models\SalesOrder;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturPenjualanSeeder extends Seeder
{
    /**
     * Seed the ReturPenjualan data for testing.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get necessary reference data
            $customers = Customer::take(3)->get();
            if ($customers->isEmpty()) {
                Log::warning('No customers found for ReturPenjualanSeeder. Seeding aborted.');
                return;
            }

            $salesOrders = SalesOrder::take(5)->get();
            if ($salesOrders->isEmpty()) {
                Log::warning('No sales orders found for ReturPenjualanSeeder. Seeding aborted.');
                return;
            }

            $adminUser = User::where('email', 'admin@example.com')->first();
            if (!$adminUser) {
                Log::warning('Admin user not found for ReturPenjualanSeeder. Using first available user.');
                $adminUser = User::first();

                if (!$adminUser) {
                    Log::error('No users found for ReturPenjualanSeeder. Seeding aborted.');
                    return;
                }
            }

            $approverUser = User::where('email', 'manager@example.com')->first() ?? $adminUser;
            $qcUser = User::where('email', 'qc@example.com')->first() ?? $adminUser;

            $produkList = Produk::take(10)->get();
            if ($produkList->isEmpty()) {
                Log::warning('No products found for ReturPenjualanSeeder. Seeding aborted.');
                return;
            }

            $satuan = Satuan::first();
            if (!$satuan) {
                Log::warning('No units found for ReturPenjualanSeeder. Seeding aborted.');
                return;
            }

            // Array of possible return reasons
            $alasanList = [
                'Produk rusak',
                'Salah kirim',
                'Jumlah tidak sesuai',
                'Kualitas di bawah standar',
                'Produk kadaluarsa',
                'Produk tidak sesuai pesanan',
                'Kemasan rusak'
            ];

            $defectTypes = [
                'Kerusakan fisik',
                'Cacat produksi',
                'Kesalahan packaging',
                'Kesalahan warna',
                'Kesalahan ukuran',
                'Kontaminasi',
                'Kinerja produk buruk'
            ];

            // Create returns with different statuses to test all workflows

            // 1. Draft return - basic return in draft status
            $this->createReturn(
                $salesOrders[0],
                $customers[0],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'draft',
                false,
                null,
                null,
                null
            );

            // 2. Return waiting for approval
            $this->createReturn(
                $salesOrders[1],
                $customers[1],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'menunggu_persetujuan',
                false,
                null,
                null,
                null
            );

            // 3. Approved return ready for QC
            $this->createReturn(
                $salesOrders[2],
                $customers[2],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'disetujui',
                true,
                null,
                null,
                null
            );

            // 4. Return with completed QC
            $this->createReturn(
                $salesOrders[3],
                $customers[0],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'disetujui',
                true,
                true,
                'Item passed quality inspection',
                $qcUser->id
            );

            // 5. Rejected return
            $this->createReturn(
                $salesOrders[4],
                $customers[1],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'ditolak',
                false,
                null,
                null,
                null
            );

            // 6. Completed return
            $this->createReturn(
                $salesOrders[0],
                $customers[2],
                $adminUser,
                $produkList,
                $satuan,
                $alasanList,
                $defectTypes,
                'selesai',
                true,
                true,
                'All items passed inspection. Credit note issued.',
                $qcUser->id
            );

            DB::commit();

            Log::info('ReturPenjualanSeeder completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in ReturPenjualanSeeder: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Helper method to create a return with details
     */
    private function createReturn(
        $salesOrder,
        $customer,
        $user,
        $produkList,
        $satuan,
        $alasanList,
        $defectTypes,
        $status = 'draft',
        $requiresQc = false,
        $qcPassed = null,
        $qcNotes = null,
        $qcByUserId = null
    ): ReturPenjualan {
        // Create the return header
        $retur = ReturPenjualan::create([
            'nomor' => 'RTR-' . date('Ymd') . '-' . rand(1000, 9999),
            'tanggal' => now(),
            'sales_order_id' => $salesOrder->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'catatan' => 'Retur penjualan yang dibuat oleh seeder untuk pengujian',
            'status' => $status,
            'requires_qc' => $requiresQc,
            'qc_passed' => $qcPassed,
            'qc_notes' => $qcNotes,
            'qc_by_user_id' => $qcByUserId,
            'qc_at' => $qcByUserId ? now() : null
        ]);

        // Create 2-4 detail items for this return
        $total = 0;
        $detailCount = rand(2, 4);

        for ($i = 0; $i < $detailCount; $i++) {
            $produk = $produkList->random();
            $quantity = rand(1, 5);
            $alasan = $alasanList[array_rand($alasanList)];
            $qcChecked = $qcByUserId ? true : false;
            $qcItemPassed = $qcPassed;

            // Create return detail
            $detail = ReturPenjualanDetail::create([
                'retur_id' => $retur->id,
                'produk_id' => $produk->id,
                'quantity' => $quantity,
                'satuan_id' => $satuan->id,
                'alasan' => $alasan,
                'keterangan' => 'Detail item untuk pengujian',
                'reason_analysis' => $alasan === 'Produk rusak' ? 'Kerusakan terjadi saat pengiriman' : null,
                'qc_checked' => $qcChecked,
                'qc_passed' => $qcItemPassed,
                'qc_notes' => $qcByUserId ? 'QC completed for this item' : null,
                'defect_type' => $alasan === 'Produk rusak' ? $defectTypes[array_rand($defectTypes)] : null,
                'qc_images' => null
            ]);

            // Calculate total (assuming we have harga in the product)
            if (isset($produk->harga_jual)) {
                $subtotal = $produk->harga_jual * $quantity;
                $total += $subtotal;
            }
        }

        // Update the total
        $retur->update(['total' => $total]);

        return $retur;
    }
}
