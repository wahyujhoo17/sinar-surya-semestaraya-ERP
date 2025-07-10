<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;

class SalesOrderStockNotificationService
{
    /**
     * Kirim notifikasi stok kurang khusus untuk Sales Order, dengan detail item dan sales order.
     *
     * @param array $stokKurang Array data stok kurang (produk_id, produk, diminta, tersedia)
     * @param int|null $gudangId
     * @param string|null $salesOrderNomor
     * @param int|null $salesOrderId
     * @param int|null $userId
     * @return void
     */
    public function notifyStockInsufficientForSalesOrder(array $stokKurang, $gudangId = null, $salesOrderNomor = null, $salesOrderId = null, $userId = null)
    {
        $roles = ['warehouse', 'purchasing', 'manager', 'admin', 'admin_penjualan', 'manager_penjualan', 'manager_pembelian'];
        $type = 'warning';
        $title = 'Stok Kurang untuk Sales Order';
        $gudangNama = null;
        if ($gudangId) {
            $gudang = \App\Models\Gudang::find($gudangId);
            $gudangNama = $gudang ? $gudang->nama : null;
        }
        $gudangText = $gudangNama ? ' (Gudang: ' . $gudangNama . ')' : '';
        $soText = $salesOrderNomor ? " pada Sales Order #$salesOrderNomor" : '';

        $message = "Terdapat item dengan stok kurang$soText$gudangText:\n";
        foreach ($stokKurang as $row) {
            $message .= "- {$row['produk']}: diminta {$row['diminta']}, tersedia {$row['tersedia']}\n";
        }

        $data = [
            'stok_kurang' => $stokKurang,
            'gudang_id' => $gudangId,
            'gudang_nama' => $gudangNama,
            'sales_order_nomor' => $salesOrderNomor,
            'sales_order_id' => $salesOrderId,
            'url' => $salesOrderId ? route('penjualan.sales-order.show', $salesOrderId) : null,
            'created_by' => $userId ?? Auth::id(),
        ];

        try {
            // Kirim ke semua user dengan role terkait
            $users = User::whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('kode', $roles);
            })->where('is_active', true)->get();

            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'link' => $data['url'],
                    'data' => json_encode($data),
                    'read_at' => null
                ]);
            }

            Log::info('Notifikasi stok kurang Sales Order dikirim', [
                'roles' => $roles,
                'user_count' => $users->count(),
                'sales_order_id' => $salesOrderId,
                'stok_kurang' => $stokKurang
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi stok kurang Sales Order', [
                'error' => $e->getMessage(),
                'sales_order_id' => $salesOrderId
            ]);
        }
    }
}
