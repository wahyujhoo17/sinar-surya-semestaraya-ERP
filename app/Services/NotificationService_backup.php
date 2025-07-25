<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to users based on their roles
     *
     * @param array $roleIds Array of role IDs or role codes
     * @param string $type Notification type (order, payment, approval, etc.)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data (URLs, IDs, etc.)
     * @return void
     */
    public function sendToRoles(array $roles, string $type, string $title, string $message, array $data = [])
    {
        try {
            // Get users with the specified roles
            $users = User::whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('kode', $roles);
            })->where('is_active', true)->get();

            foreach ($users as $user) {
                $this->createNotification($user->id, $type, $title, $message, $data);
            }

            Log::info('Notifications sent to roles', [
                'roles' => $roles,
                'type' => $type,
                'user_count' => $users->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notifications to roles', [
                'roles' => $roles,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification to specific users
     *
     * @param array $userIds Array of user IDs
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return void
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $data = [])
    {
        try {
            foreach ($userIds as $userId) {
                $this->createNotification($userId, $type, $title, $message, $data);
            }

            Log::info('Notifications sent to users', [
                'user_ids' => $userIds,
                'type' => $type,
                'count' => count($userIds)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notifications to users', [
                'user_ids' => $userIds,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send Sales Order notifications
     */
    public function notifySalesOrderCreated($salesOrder, $createdBy)
    {
        $customerName = $salesOrder->customer->nama ?? $salesOrder->customer->company ?? 'Customer';

        // Notify manager_penjualan about new sales order
        $this->sendToRoles(
            ['manager_penjualan', 'admin_penjualan'],
            'order',
            'Sales Order Baru',
            "Sales Order #{$salesOrder->nomor} telah dibuat untuk customer {$customerName} dengan total " .
                "Rp " . number_format($salesOrder->total, 0, ',', '.') . " oleh {$createdBy->name}",
            [
                'url' => route('penjualan.sales-order.show', $salesOrder->id),
                'sales_order_id' => $salesOrder->id,
                'customer_id' => $salesOrder->customer_id,
                'created_by' => $createdBy->id
            ]
        );

        // Notify admin
        $this->sendToRoles(
            ['admin', 'purchasing', 'warehouse'],
            'order',
            'Sales Order Baru',
            "Sales Order #{$salesOrder->nomor} dibuat oleh {$createdBy->name} untuk {$customerName}",
            [
                'url' => route('penjualan.sales-order.show', $salesOrder->id),
                'sales_order_id' => $salesOrder->id
            ]
        );
    }

    /**
     * Send Delivery Order notifications
     */
    public function notifyDeliveryOrderShipped($deliveryOrder, $shippedBy)
    {
        $customerName = $deliveryOrder->customer->nama ?? $deliveryOrder->customer->company ?? 'Customer';

        // Notify manager_penjualan about shipped delivery order
        $this->sendToRoles(
            ['manager_penjualan'],
            'order',
            'Delivery Order Dikirim',
            "Delivery Order #{$deliveryOrder->nomor} telah dikirim untuk customer {$customerName} oleh {$shippedBy->name}",
            [
                'url' => route('penjualan.delivery-order.show', $deliveryOrder->id),
                'delivery_order_id' => $deliveryOrder->id,
                'sales_order_id' => $deliveryOrder->sales_order_id
            ]
        );
    }

    /**
     * Send Purchase Order notifications
     */
    public function notifyPurchaseOrderCreated($purchaseOrder, $createdBy)
    {
        $supplierName = $purchaseOrder->supplier->nama ?? 'Supplier';

        // Notify manager about new purchase order
        $this->sendToRoles(
            ['manager', 'admin'],
            'order',
            'Purchase Order Baru',
            "Purchase Order #{$purchaseOrder->nomor} telah dibuat untuk supplier {$supplierName} dengan total " .
                "Rp " . number_format($purchaseOrder->total, 0, ',', '.') . " oleh {$createdBy->name}",
            [
                'url' => route('pembelian.purchasing-order.show', $purchaseOrder->id),
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_id' => $purchaseOrder->supplier_id
            ]
        );
    }

    /**
     * Send approval notifications
     */
    public function notifyApprovalRequired($itemType, $item, $approverRoles)
    {
        $title = "Persetujuan Diperlukan";
        $message = "";

        switch ($itemType) {
            case 'sales_order':
                $message = "Sales Order #{$item->nomor} memerlukan persetujuan";
                $url = route('penjualan.sales-order.show', $item->id);
                break;
            case 'purchase_order':
                $message = "Purchase Order #{$item->nomor} memerlukan persetujuan";
                $url = route('pembelian.purchasing-order.show', $item->id);
                break;
            case 'retur_penjualan':
                $message = "Retur Penjualan #{$item->nomor} memerlukan persetujuan";
                $url = route('penjualan.retur.show', $item->id);
                break;
            case 'perencanaan_produksi':
                $message = "Perencanaan Produksi #{$item->nomor} memerlukan persetujuan";
                $url = route('produksi.perencanaan-produksi.show', $item->id);
                break;
            default:
                $message = "Item memerlukan persetujuan";
                $url = '#';
        }

        $this->sendToRoles(
            $approverRoles,
            'warning',
            $title,
            $message,
            [
                'url' => $url,
                'item_type' => $itemType,
                'item_id' => $item->id,
                'requires_approval' => true
            ]
        );
    }

    /**
     * Send production planning notifications
     */
    public function notifyProductionPlanCreated($productionPlan, $createdBy)
    {
        // Notify production manager and admin
        $this->sendToRoles(
            ['manager_produksi', 'admin', 'production'],
            'order',
            'Perencanaan Produksi Baru',
            "Perencanaan Produksi #{$productionPlan->nomor} telah dibuat oleh {$createdBy->name}",
            [
                'url' => route('produksi.perencanaan-produksi.show', $productionPlan->id),
                'production_plan_id' => $productionPlan->id,
                'sales_order_id' => $productionPlan->sales_order_id ?? null
            ]
        );
    }

    /**
     * Send stock alerts
     */
    public function notifyLowStock($product, $currentStock, $minStock)
    {
        $this->sendToRoles(
            ['warehouse', 'purchasing', 'manager', 'admin'],
            'warning',
            'Stok Menipis',
            "Stok produk {$product->nama} tersisa {$currentStock} unit (minimum: {$minStock})",
            [
                'url' => route('inventaris.stok.index'),
                'product_id' => $product->id,
                'current_stock' => $currentStock,
                'min_stock' => $minStock
            ]
        );
    }

    /**
     * Send payment notifications
     */
    public function notifyPaymentReceived($payment, $invoice)
    {
        $customerName = $invoice->customer->nama ?? $invoice->customer->company ?? 'Customer';

        $this->sendToRoles(
            ['finance', 'manager_penjualan', 'admin'],
            'payment',
            'Pembayaran Diterima',
            "Pembayaran sebesar Rp " . number_format($payment->jumlah, 0, ',', '.') .
                " diterima dari {$customerName} untuk Invoice #{$invoice->nomor}",
            [
                'url' => route('keuangan.pembayaran-piutang.show', $payment->id),
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id
            ]
        );
    }

    /**
     * Send purchase request notifications
     */
    public function notifyPurchaseRequestSubmitted($purchaseRequest, $submittedBy)
    {
        $departmentName = $purchaseRequest->department->nama ?? 'Department';

        // Notify purchasing and managers when PR is submitted for approval
        $this->sendToRoles(
            ['purchasing', 'manager_pembelian', 'admin'],
            'order',
            'Permintaan Pembelian Diajukan',
            "Permintaan Pembelian #{$purchaseRequest->nomor} telah diajukan oleh {$submittedBy->name} dari {$departmentName}",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id,
                'department_id' => $purchaseRequest->department_id,
                'submitted_by' => $submittedBy->id
            ]
        );
    }

    public function notifyPurchaseRequestApproved($purchaseRequest, $approvedBy)
    {
        $departmentName = $purchaseRequest->department->nama ?? 'Department';

        // Notify the requestor when PR is approved
        $this->sendToUsers(
            [$purchaseRequest->user_id],
            'success',
            'Permintaan Pembelian Disetujui',
            "Permintaan Pembelian #{$purchaseRequest->nomor} Anda telah disetujui oleh {$approvedBy->name}",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id
            ]
        );

        // Also notify purchasing team
        $this->sendToRoles(
            ['purchasing'],
            'success',
            'Permintaan Pembelian Disetujui',
            "Permintaan Pembelian #{$purchaseRequest->nomor} dari {$departmentName} telah disetujui dan siap diproses",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id
            ]
        );
    }

    public function notifyPurchaseRequestRejected($purchaseRequest, $rejectedBy, $reason = null)
    {
        $reasonText = $reason ? " dengan alasan: {$reason}" : '';

        // Notify the requestor when PR is rejected
        $this->sendToUsers(
            [$purchaseRequest->user_id],
            'warning',
            'Permintaan Pembelian Ditolak',
            "Permintaan Pembelian #{$purchaseRequest->nomor} Anda telah ditolak oleh {$rejectedBy->name}{$reasonText}",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id,
                'rejection_reason' => $reason
            ]
        );
    }

    public function notifyPurchaseRequestCompleted($purchaseRequest, $completedBy)
    {
        $departmentName = $purchaseRequest->department->nama ?? 'Department';

        // Notify the requestor when PR is completed
        $this->sendToUsers(
            [$purchaseRequest->user_id],
            'success',
            'Permintaan Pembelian Selesai',
            "Permintaan Pembelian #{$purchaseRequest->nomor} Anda telah selesai diproses",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id
            ]
        );

        // Also notify managers
        $this->sendToRoles(
            ['manager_pembelian', 'admin'],
            'success',
            'Permintaan Pembelian Selesai',
            "Permintaan Pembelian #{$purchaseRequest->nomor} dari {$departmentName} telah selesai diproses",
            [
                'url' => route('pembelian.permintaan-pembelian.show', $purchaseRequest->id),
                'purchase_request_id' => $purchaseRequest->id
            ]
        );
    }

    /**
     * Create notification record
     *
     * @param int $userId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @return void
     */
    private function createNotification(int $userId, string $type, string $title, string $message, array $data = [])
    {
        try {
            // Fix URL to use current domain instead of localhost
            $url = $data['url'] ?? null;
            if ($url) {
                $url = $this->fixNotificationUrl($url);
            }

            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $url,
                'data' => json_encode($data),
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fix notification URL to use current domain instead of localhost
     */
    private function fixNotificationUrl($url)
    {
        if (!$url) {
            return $url;
        }

        $originalUrl = $url;

        // Normalize multiple :8000 in the URL (fixes http://localhost:8000:8000:8000/...)
        $url = preg_replace('/(:\d+)(:\d+)+/', '$1', $url);

        // If URL is absolute and contains localhost, replace with current domain
        // Remove duplicate ports in localhost URLs (e.g., :8000:8000:8000)
        if (is_string($url)) {
            // Replace any sequence of :port repeated with just one
            $url = preg_replace('/(:\d+)(:\d+)+/', '$1', $url);

            // If still starts with localhost, replace with current domain
            if (preg_match('#^https?://localhost(:\d+)?#', $url)) {
                $currentUrl = request()->getSchemeAndHttpHost();
                // Remove everything up to and including the last port in localhost
                $url = preg_replace('#^https?://localhost(:\d+)?#', $currentUrl, $url);

                Log::info('Fixed notification URL from localhost', [
                    'original' => $originalUrl,
                    'fixed' => $url,
                    'current_domain' => $currentUrl
                ]);
            }
        }

        // Also handle cases where APP_URL is localhost but request is from different domain
        $appUrl = config('app.url');
        if ($appUrl && strpos($appUrl, 'localhost') !== false && is_string($url) && strpos($url, $appUrl) === 0) {
            $currentUrl = request()->getSchemeAndHttpHost();
            $url = str_replace($appUrl, $currentUrl, $url);

            Log::info('Fixed notification URL from APP_URL localhost', [
                'original' => $originalUrl,
                'fixed' => $url,
                'app_url' => $appUrl,
                'current_domain' => $currentUrl
            ]);
        }

        return $url;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId = null)
    {
        $query = Notification::where('id', $notificationId);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $query->update(['read_at' => now()]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Send work order completion notifications
     */
    public function notifyWorkOrderCompleted($workOrder, $completedBy)
    {
        $produkName = $workOrder->produk->nama ?? 'Produk';
        $jumlahProduksi = $workOrder->qualityControl->jumlah_lolos ?? $workOrder->jumlah_produksi;

        // Notify production managers and admin
        $this->sendToRoles(
            ['production', 'manager', 'admin'],
            'success',
            'Work Order Selesai',
            "Work Order #{$workOrder->nomor} telah selesai. Produksi {$produkName} sebanyak {$jumlahProduksi} unit berhasil diselesaikan oleh {$completedBy->name}",
            [
                'url' => route('produksi.work-order.show', $workOrder->id),
                'work_order_id' => $workOrder->id,
                'produk_id' => $workOrder->produk_id,
                'jumlah_produksi' => $jumlahProduksi
            ]
        );

        // Notify warehouse about new stock
        $this->sendToRoles(
            ['warehouse'],
            'order',
            'Stok Produk Bertambah',
            "Stok produk {$produkName} bertambah {$jumlahProduksi} unit dari hasil produksi Work Order #{$workOrder->nomor}",
            [
                'url' => route('inventaris.stok.index'),
                'work_order_id' => $workOrder->id,
                'produk_id' => $workOrder->produk_id,
                'jumlah_produksi' => $jumlahProduksi
            ]
        );
    }

    /**
     * Check and notify low stock after stock operations
     */
    public function checkAndNotifyLowStock($produkId, $gudangId = null)
    {
        try {
            $produk = \App\Models\Produk::with('stok')->find($produkId);

            if (!$produk || !$produk->stok_minimum) {
                return; // Skip if product not found or no minimum stock set
            }

            // Check total stock across all warehouses
            $totalStock = $produk->total_stok;

            if ($totalStock <= $produk->stok_minimum && $totalStock > 0) {
                $this->notifyLowStock($produk, $totalStock, $produk->stok_minimum);
            } elseif ($totalStock <= 0) {
                // Product is out of stock completely
                $this->sendToRoles(
                    ['warehouse', 'purchasing', 'manager', 'admin', 'admin_penjualan', 'manager_penjualan'],
                    'warning',
                    'Stok Habis',
                    "Produk {$produk->nama} telah habis di semua gudang",
                    [
                        'url' => route('inventaris.stok.index'),
                        'product_id' => $produk->id,
                        'current_stock' => 0,
                        'min_stock' => $produk->stok_minimum
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to check low stock', [
                'produk_id' => $produkId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clean old notifications (older than specified days)
     */
    public function cleanOldNotifications($daysOld = 30)
    {
        try {
            $deleted = Notification::where('created_at', '<', now()->subDays($daysOld))->delete();

            Log::info('Cleaned old notifications', [
                'days_old' => $daysOld,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Failed to clean old notifications', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Send stock adjustment notifications
     */
    public function notifyStockAdjustmentCreated($stockAdjustment, $createdBy)
    {
        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $totalItems = $stockAdjustment->details->count();

        // Notify warehouse and inventory managers
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'order',
            'Penyesuaian Stok Baru',
            "Penyesuaian Stok #{$stockAdjustment->nomor} telah dibuat oleh {$createdBy->name} untuk {$gudangNama} dengan {$totalItems} item",
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'gudang_id' => $stockAdjustment->gudang_id,
                'total_items' => $totalItems
            ]
        );
    }

    public function notifyStockAdjustmentProcessed($stockAdjustment, $processedBy)
    {
        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $totalItems = $stockAdjustment->details->count();

        // Calculate adjustment summary
        $positiveAdjustments = $stockAdjustment->details->where('selisih', '>', 0)->count();
        $negativeAdjustments = $stockAdjustment->details->where('selisih', '<', 0)->count();
        $noChanges = $stockAdjustment->details->where('selisih', 0)->count();

        $summaryText = [];
        if ($positiveAdjustments > 0) {
            $summaryText[] = "{$positiveAdjustments} item bertambah";
        }
        if ($negativeAdjustments > 0) {
            $summaryText[] = "{$negativeAdjustments} item berkurang";
        }
        if ($noChanges > 0) {
            $summaryText[] = "{$noChanges} item tidak berubah";
        }

        $summary = implode(', ', $summaryText);

        // Notify warehouse, inventory managers, and admin
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'success',
            'Penyesuaian Stok Diproses',
            "Penyesuaian Stok #{$stockAdjustment->nomor} telah diproses oleh {$processedBy->name} untuk {$gudangNama}. Ringkasan: {$summary}",
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'gudang_id' => $stockAdjustment->gudang_id,
                'processed_by' => $processedBy->id
            ]
        );

        // Notify the creator
        if ($stockAdjustment->user_id !== $processedBy->id) {
            $this->sendToUsers(
                [$stockAdjustment->user_id],
                'success',
                'Penyesuaian Stok Anda Diproses',
                "Penyesuaian Stok #{$stockAdjustment->nomor} yang Anda buat telah diproses oleh {$processedBy->name}",
                [
                    'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                    'stock_adjustment_id' => $stockAdjustment->id
                ]
            );
        }
    }

    public function notifyStockAdjustmentDeleted($stockAdjustmentData, $deletedBy)
    {
        // Notify warehouse and inventory managers about deletion
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'warning',
            'Penyesuaian Stok Dihapus',
            "Penyesuaian Stok #{$stockAdjustmentData['nomor']} telah dihapus oleh {$deletedBy->name} dari {$stockAdjustmentData['gudang']}",
            [
                'url' => route('inventaris.penyesuaian-stok.index'),
                'deleted_by' => $deletedBy->id,
                'gudang_name' => $stockAdjustmentData['gudang']
            ]
        );
    }

    /**
     * Check and notify significant stock discrepancies during adjustment
     */
    public function notifyStockDiscrepancy($stockAdjustment, $discrepancyItems)
    {
        if (empty($discrepancyItems)) {
            return;
        }

        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $discrepancyCount = count($discrepancyItems);

        // Create message with discrepancy details
        $message = "Ditemukan {$discrepancyCount} item dengan selisih besar pada Penyesuaian Stok #{$stockAdjustment->nomor} di {$gudangNama}:";

        foreach ($discrepancyItems as $item) {
            $message .= "\n- {$item['nama']}: selisih {$item['selisih']} unit";
        }

        // Notify managers and admin about significant discrepancies
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager', 'admin'],
            'warning',
            'Selisih Stok Signifikan',
            $message,
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'discrepancy_count' => $discrepancyCount,
                'gudang_id' => $stockAdjustment->gudang_id
            ]
        );
    }

    /**
     * Send Daily Aktivitas notifications
     */
    public function notifyDailyAktivitasCreated($dailyAktivitas, $createdBy, $assignedUserIds = [])
    {
        if (empty($assignedUserIds)) {
            return;
        }

        $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
        $assignedNames = implode(', ', $assignedUsers);

        // Notify assigned users
        $this->sendToUsers(
            $assignedUserIds,
            'order',
            'Aktivitas Baru Ditugaskan',
            "Anda ditugaskan pada aktivitas '{$dailyAktivitas->judul}' oleh {$createdBy->name}. " .
                "Status: {$dailyAktivitas->status}" .
                ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'assigned_by' => $createdBy->id,
                'deadline' => $dailyAktivitas->deadline?->toISOString()
            ]
        );

        // Notify managers about new task assignment
        $this->sendToRoles(
            ['manager', 'admin'],
            'order',
            'Aktivitas Baru Dibuat',
            "{$createdBy->name} membuat aktivitas '{$dailyAktivitas->judul}' dan menugaskan ke: {$assignedNames}",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'created_by' => $createdBy->id,
                'assigned_users_count' => count($assignedUserIds)
            ]
        );
    }

    public function notifyDailyAktivitasUpdated($dailyAktivitas, $updatedBy, $changes = [])
    {
        // Get all assigned users
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $changeText = '';
        if (!empty($changes)) {
            $changeMessages = [];
            foreach ($changes as $field => $change) {
                if ($field === 'deadline') {
                    $changeMessages[] = "Deadline: {$change['old']} → {$change['new']}";
                } elseif ($field === 'prioritas') {
                    $changeMessages[] = "Prioritas: {$change['old']} → {$change['new']}";
                } elseif ($field === 'judul') {
                    $changeMessages[] = "Judul diubah";
                } elseif ($field === 'deskripsi') {
                    $changeMessages[] = "Deskripsi diubah";
                }
            }
            $changeText = ' Perubahan: ' . implode(', ', $changeMessages);
        }

        // Notify assigned users (except the one who updated)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($updatedBy) {
            return $id !== $updatedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Aktivitas Diperbarui',
                "Aktivitas '{$dailyAktivitas->judul}' telah diperbarui oleh {$updatedBy->name}.{$changeText}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'updated_by' => $updatedBy->id,
                    'changes' => $changes
                ]
            );
        }
    }

    public function notifyDailyAktivitasStatusChanged($dailyAktivitas, $oldStatus, $newStatus, $changedBy)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $statusMessages = [
            'todo' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'review' => 'Review',
            'done' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        $oldStatusText = $statusMessages[$oldStatus] ?? $oldStatus;
        $newStatusText = $statusMessages[$newStatus] ?? $newStatus;

        $notificationType = 'order';
        if ($newStatus === 'done') {
            $notificationType = 'success';
        } elseif ($newStatus === 'cancelled') {
            $notificationType = 'warning';
        }

        // Notify assigned users (except the one who changed)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($changedBy) {
            return $id !== $changedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                $notificationType,
                'Status Aktivitas Berubah',
                "Status aktivitas '{$dailyAktivitas->judul}' berubah dari {$oldStatusText} menjadi {$newStatusText} oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => $changedBy->id
                ]
            );
        }

        // Notify managers when task is completed
        if ($newStatus === 'done') {
            $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
            $assignedNames = implode(', ', $assignedUsers);

            $this->sendToRoles(
                ['manager', 'admin'],
                'success',
                'Aktivitas Selesai',
                "Aktivitas '{$dailyAktivitas->judul}' telah diselesaikan oleh {$changedBy->name}. Tim: {$assignedNames}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'completed_by' => $changedBy->id
                ]
            );
        }
    }

    public function notifyDailyAktivitasDeadlineApproaching($dailyAktivitas, $hoursRemaining)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $timeText = $hoursRemaining <= 24 ? 
            ($hoursRemaining <= 1 ? 'kurang dari 1 jam' : "{$hoursRemaining} jam") :
            round($hoursRemaining / 24) . ' hari';

        $this->sendToUsers(
            $assignedUserIds,
            'warning',
            'Deadline Mendekati',
            "Aktivitas '{$dailyAktivitas->judul}' akan berakhir dalam {$timeText} (Deadline: {$dailyAktivitas->deadline->format('d/m/Y H:i')})",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'deadline' => $dailyAktivitas->deadline->toISOString(),
                'hours_remaining' => $hoursRemaining
            ]
        );
    }

    public function notifyDailyAktivitasOverdue($dailyAktivitas)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $hoursOverdue = now()->diffInHours($dailyAktivitas->deadline);
        $overdueText = $hoursOverdue <= 24 ? 
            "{$hoursOverdue} jam" : 
            round($hoursOverdue / 24) . ' hari';

        $this->sendToUsers(
            $assignedUserIds,
            'warning',
            'Aktivitas Terlambat',
            "Aktivitas '{$dailyAktivitas->judul}' sudah terlambat {$overdueText} dari deadline ({$dailyAktivitas->deadline->format('d/m/Y H:i')})",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'deadline' => $dailyAktivitas->deadline->toISOString(),
                'hours_overdue' => $hoursOverdue
            ]
        );

        // Also notify managers about overdue tasks
        $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
        $assignedNames = implode(', ', $assignedUsers);

        $this->sendToRoles(
            ['manager', 'admin'],
            'warning',
            'Aktivitas Terlambat',
            "Aktivitas '{$dailyAktivitas->judul}' terlambat {$overdueText}. Tim yang ditugaskan: {$assignedNames}",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'hours_overdue' => $hoursOverdue,
                'assigned_users_count' => count($assignedUserIds)
            ]
        );
    }

    public function notifyDailyAktivitasAssignmentChanged($dailyAktivitas, $addedUsers, $removedUsers, $changedBy)
    {
        // Notify newly added users
        if (!empty($addedUsers)) {
            $this->sendToUsers(
                $addedUsers,
                'order',
                'Ditugaskan ke Aktivitas',
                "Anda ditugaskan ke aktivitas '{$dailyAktivitas->judul}' oleh {$changedBy->name}. " .
                    "Status: {$dailyAktivitas->status}" .
                    ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'assigned_by' => $changedBy->id
                ]
            );
        }

        // Notify removed users
        if (!empty($removedUsers)) {
            $this->sendToUsers(
                $removedUsers,
                'order',
                'Penugasan Dihapus',
                "Penugasan Anda pada aktivitas '{$dailyAktivitas->judul}' telah dihapus oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.index'),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'removed_by' => $changedBy->id
                ]
            );
        }

        // Notify remaining assigned users about team changes
        $currentAssignedIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        $notifyUserIds = array_filter($currentAssignedIds, function($id) use ($changedBy, $addedUsers) {
            return $id !== $changedBy->id && !in_array($id, $addedUsers);
        });

        if (!empty($notifyUserIds) && (!empty($addedUsers) || !empty($removedUsers))) {
            $changeText = [];
            if (!empty($addedUsers)) {
                $addedNames = User::whereIn('id', $addedUsers)->pluck('name')->toArray();
                $changeText[] = 'Ditambahkan: ' . implode(', ', $addedNames);
            }
            if (!empty($removedUsers)) {
                $removedNames = User::whereIn('id', $removedUsers)->pluck('name')->toArray();
                $changeText[] = 'Dihapus: ' . implode(', ', $removedNames);
            }

            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Tim Aktivitas Berubah',
                "Tim aktivitas '{$dailyAktivitas->judul}' diubah oleh {$changedBy->name}. " . implode('. ', $changeText),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'changed_by' => $changedBy->id,
                    'added_users' => $addedUsers,
                    'removed_users' => $removedUsers
                ]
            );
        }
    }

    public function notifyDailyAktivitasCommentAdded($dailyAktivitas, $comment, $commentBy)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        // Notify assigned users (except the commenter)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($commentBy) {
            return $id !== $commentBy->id;
        });

        if (!empty($notifyUserIds)) {
            $commentPreview = strlen($comment) > 100 ? 
                substr($comment, 0, 100) . '...' : 
                $comment;

            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Komentar Baru',
                "{$commentBy->name} menambahkan komentar pada aktivitas '{$dailyAktivitas->judul}': \"{$commentPreview}\"",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'comment_by' => $commentBy->id,
                    'comment_preview' => $commentPreview
                ]
            );
        }
    }

    /**
     * Check and send Daily Aktivitas deadline notifications (for scheduler)
     */
    public function checkDailyAktivitasDeadlines()
    {
        try {
            $now = now();
            
            // Find activities with deadlines in next 24 hours (not yet notified today)
            $upcomingActivities = \App\Models\DailyAktivitas::where('status', '!=', 'done')
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('deadline')
                ->where('deadline', '>', $now)
                ->where('deadline', '<=', $now->copy()->addHours(24))
                ->whereDoesntHave('assignedUsers', function($query) use ($now) {
                    $query->where('notified_at', '>=', $now->startOfDay());
                })
                ->get();

            foreach ($upcomingActivities as $aktivitas) {
                $hoursRemaining = $now->diffInHours($aktivitas->deadline);
                $this->notifyDailyAktivitasDeadlineApproaching($aktivitas, $hoursRemaining);
                
                // Mark as notified today
                $aktivitas->assignedUsers()->update([
                    'notified_at' => $now,
                    'notification_sent' => true
                ]);
            }

            // Find overdue activities (not yet marked as overdue today)
            $overdueActivities = \App\Models\DailyAktivitas::where('status', '!=', 'done')
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('deadline')
                ->where('deadline', '<', $now)
                ->whereDoesntHave('assignedUsers', function($query) use ($now) {
                    $query->where('notified_at', '>=', $now->startOfDay())
                          ->where('notification_sent', true);
                })
                ->get();

            foreach ($overdueActivities as $aktivitas) {
                $this->notifyDailyAktivitasOverdue($aktivitas);
                
                // Mark as notified today
                $aktivitas->assignedUsers()->update([
                    'notified_at' => $now,
                    'notification_sent' => true
                ]);
            }

            Log::info('Daily Aktivitas deadline check completed', [
                'upcoming_count' => $upcomingActivities->count(),
                'overdue_count' => $overdueActivities->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to check Daily Aktivitas deadlines', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clean old notifications (older than specified days)
     */
    public function cleanOldNotifications($daysOld = 30)
    {
        try {
            $deleted = Notification::where('created_at', '<', now()->subDays($daysOld))->delete();

            Log::info('Cleaned old notifications', [
                'days_old' => $daysOld,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Failed to clean old notifications', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Send stock adjustment notifications
     */
    public function notifyStockAdjustmentCreated($stockAdjustment, $createdBy)
    {
        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $totalItems = $stockAdjustment->details->count();

        // Notify warehouse and inventory managers
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'order',
            'Penyesuaian Stok Baru',
            "Penyesuaian Stok #{$stockAdjustment->nomor} telah dibuat oleh {$createdBy->name} untuk {$gudangNama} dengan {$totalItems} item",
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'gudang_id' => $stockAdjustment->gudang_id,
                'total_items' => $totalItems
            ]
        );
    }

    public function notifyStockAdjustmentProcessed($stockAdjustment, $processedBy)
    {
        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $totalItems = $stockAdjustment->details->count();

        // Calculate adjustment summary
        $positiveAdjustments = $stockAdjustment->details->where('selisih', '>', 0)->count();
        $negativeAdjustments = $stockAdjustment->details->where('selisih', '<', 0)->count();
        $noChanges = $stockAdjustment->details->where('selisih', 0)->count();

        $summaryText = [];
        if ($positiveAdjustments > 0) {
            $summaryText[] = "{$positiveAdjustments} item bertambah";
        }
        if ($negativeAdjustments > 0) {
            $summaryText[] = "{$negativeAdjustments} item berkurang";
        }
        if ($noChanges > 0) {
            $summaryText[] = "{$noChanges} item tidak berubah";
        }

        $summary = implode(', ', $summaryText);

        // Notify warehouse, inventory managers, and admin
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'success',
            'Penyesuaian Stok Diproses',
            "Penyesuaian Stok #{$stockAdjustment->nomor} telah diproses oleh {$processedBy->name} untuk {$gudangNama}. Ringkasan: {$summary}",
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'gudang_id' => $stockAdjustment->gudang_id,
                'processed_by' => $processedBy->id
            ]
        );

        // Notify the creator
        if ($stockAdjustment->user_id !== $processedBy->id) {
            $this->sendToUsers(
                [$stockAdjustment->user_id],
                'success',
                'Penyesuaian Stok Anda Diproses',
                "Penyesuaian Stok #{$stockAdjustment->nomor} yang Anda buat telah diproses oleh {$processedBy->name}",
                [
                    'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                    'stock_adjustment_id' => $stockAdjustment->id
                ]
            );
        }
    }

    public function notifyStockAdjustmentDeleted($stockAdjustmentData, $deletedBy)
    {
        // Notify warehouse and inventory managers about deletion
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager_gudang', 'admin'],
            'warning',
            'Penyesuaian Stok Dihapus',
            "Penyesuaian Stok #{$stockAdjustmentData['nomor']} telah dihapus oleh {$deletedBy->name} dari {$stockAdjustmentData['gudang']}",
            [
                'url' => route('inventaris.penyesuaian-stok.index'),
                'deleted_by' => $deletedBy->id,
                'gudang_name' => $stockAdjustmentData['gudang']
            ]
        );
    }

    /**
     * Check and notify significant stock discrepancies during adjustment
     */
    public function notifyStockDiscrepancy($stockAdjustment, $discrepancyItems)
    {
        if (empty($discrepancyItems)) {
            return;
        }

        $gudangNama = $stockAdjustment->gudang->nama ?? 'Gudang';
        $discrepancyCount = count($discrepancyItems);

        // Create message with discrepancy details
        $message = "Ditemukan {$discrepancyCount} item dengan selisih besar pada Penyesuaian Stok #{$stockAdjustment->nomor} di {$gudangNama}:";

        foreach ($discrepancyItems as $item) {
            $message .= "\n- {$item['nama']}: selisih {$item['selisih']} unit";
        }

        // Notify managers and admin about significant discrepancies
        $this->sendToRoles(
            ['warehouse', 'inventory', 'manager', 'admin'],
            'warning',
            'Selisih Stok Signifikan',
            $message,
            [
                'url' => route('inventaris.penyesuaian-stok.show', $stockAdjustment->id),
                'stock_adjustment_id' => $stockAdjustment->id,
                'discrepancy_count' => $discrepancyCount,
                'gudang_id' => $stockAdjustment->gudang_id
            ]
        );
    }

    /**
     * Send Daily Aktivitas notifications
     */
    public function notifyDailyAktivitasCreated($dailyAktivitas, $createdBy, $assignedUserIds = [])
    {
        if (empty($assignedUserIds)) {
            return;
        }

        $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
        $assignedNames = implode(', ', $assignedUsers);

        // Notify assigned users
        $this->sendToUsers(
            $assignedUserIds,
            'order',
            'Aktivitas Baru Ditugaskan',
            "Anda ditugaskan pada aktivitas '{$dailyAktivitas->judul}' oleh {$createdBy->name}. " .
                "Status: {$dailyAktivitas->status}" .
                ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'assigned_by' => $createdBy->id,
                'deadline' => $dailyAktivitas->deadline?->toISOString()
            ]
        );

        // Notify managers about new task assignment
        $this->sendToRoles(
            ['manager', 'admin'],
            'order',
            'Aktivitas Baru Dibuat',
            "{$createdBy->name} membuat aktivitas '{$dailyAktivitas->judul}' dan menugaskan ke: {$assignedNames}",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'created_by' => $createdBy->id,
                'assigned_users_count' => count($assignedUserIds)
            ]
        );
    }

    public function notifyDailyAktivitasUpdated($dailyAktivitas, $updatedBy, $changes = [])
    {
        // Get all assigned users
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $changeText = '';
        if (!empty($changes)) {
            $changeMessages = [];
            foreach ($changes as $field => $change) {
                if ($field === 'deadline') {
                    $changeMessages[] = "Deadline: {$change['old']} → {$change['new']}";
                } elseif ($field === 'prioritas') {
                    $changeMessages[] = "Prioritas: {$change['old']} → {$change['new']}";
                } elseif ($field === 'judul') {
                    $changeMessages[] = "Judul diubah";
                } elseif ($field === 'deskripsi') {
                    $changeMessages[] = "Deskripsi diubah";
                }
            }
            $changeText = ' Perubahan: ' . implode(', ', $changeMessages);
        }

        // Notify assigned users (except the one who updated)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($updatedBy) {
            return $id !== $updatedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Aktivitas Diperbarui',
                "Aktivitas '{$dailyAktivitas->judul}' telah diperbarui oleh {$updatedBy->name}.{$changeText}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'updated_by' => $updatedBy->id,
                    'changes' => $changes
                ]
            );
        }
    }

    public function notifyDailyAktivitasStatusChanged($dailyAktivitas, $oldStatus, $newStatus, $changedBy)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $statusMessages = [
            'todo' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'review' => 'Review',
            'done' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        $oldStatusText = $statusMessages[$oldStatus] ?? $oldStatus;
        $newStatusText = $statusMessages[$newStatus] ?? $newStatus;

        $notificationType = 'order';
        if ($newStatus === 'done') {
            $notificationType = 'success';
        } elseif ($newStatus === 'cancelled') {
            $notificationType = 'warning';
        }

        // Notify assigned users (except the one who changed)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($changedBy) {
            return $id !== $changedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                $notificationType,
                'Status Aktivitas Berubah',
                "Status aktivitas '{$dailyAktivitas->judul}' berubah dari {$oldStatusText} menjadi {$newStatusText} oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => $changedBy->id
                ]
            );
        }

        // Notify managers when task is completed
        if ($newStatus === 'done') {
            $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
            $assignedNames = implode(', ', $assignedUsers);

            $this->sendToRoles(
                ['manager', 'admin'],
                'success',
                'Aktivitas Selesai',
                "Aktivitas '{$dailyAktivitas->judul}' telah diselesaikan oleh {$changedBy->name}. Tim: {$assignedNames}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'completed_by' => $changedBy->id
                ]
            );
        }
    }

    public function notifyDailyAktivitasDeadlineApproaching($dailyAktivitas, $hoursRemaining)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $timeText = $hoursRemaining <= 24 ? 
            ($hoursRemaining <= 1 ? 'kurang dari 1 jam' : "{$hoursRemaining} jam") :
            round($hoursRemaining / 24) . ' hari';

        $this->sendToUsers(
            $assignedUserIds,
            'warning',
            'Deadline Mendekati',
            "Aktivitas '{$dailyAktivitas->judul}' akan berakhir dalam {$timeText} (Deadline: {$dailyAktivitas->deadline->format('d/m/Y H:i')})",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'deadline' => $dailyAktivitas->deadline->toISOString(),
                'hours_remaining' => $hoursRemaining
            ]
        );
    }

    public function notifyDailyAktivitasOverdue($dailyAktivitas)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        if (empty($assignedUserIds)) {
            return;
        }

        $hoursOverdue = now()->diffInHours($dailyAktivitas->deadline);
        $overdueText = $hoursOverdue <= 24 ? 
            "{$hoursOverdue} jam" : 
            round($hoursOverdue / 24) . ' hari';

        $this->sendToUsers(
            $assignedUserIds,
            'warning',
            'Aktivitas Terlambat',
            "Aktivitas '{$dailyAktivitas->judul}' sudah terlambat {$overdueText} dari deadline ({$dailyAktivitas->deadline->format('d/m/Y H:i')})",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'deadline' => $dailyAktivitas->deadline->toISOString(),
                'hours_overdue' => $hoursOverdue
            ]
        );

        // Also notify managers about overdue tasks
        $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
        $assignedNames = implode(', ', $assignedUsers);

        $this->sendToRoles(
            ['manager', 'admin'],
            'warning',
            'Aktivitas Terlambat',
            "Aktivitas '{$dailyAktivitas->judul}' terlambat {$overdueText}. Tim yang ditugaskan: {$assignedNames}",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'hours_overdue' => $hoursOverdue,
                'assigned_users_count' => count($assignedUserIds)
            ]
        );
    }

    public function notifyDailyAktivitasAssignmentChanged($dailyAktivitas, $addedUsers, $removedUsers, $changedBy)
    {
        // Notify newly added users
        if (!empty($addedUsers)) {
            $this->sendToUsers(
                $addedUsers,
                'order',
                'Ditugaskan ke Aktivitas',
                "Anda ditugaskan ke aktivitas '{$dailyAktivitas->judul}' oleh {$changedBy->name}. " .
                    "Status: {$dailyAktivitas->status}" .
                    ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'assigned_by' => $changedBy->id
                ]
            );
        }

        // Notify removed users
        if (!empty($removedUsers)) {
            $this->sendToUsers(
                $removedUsers,
                'order',
                'Penugasan Dihapus',
                "Penugasan Anda pada aktivitas '{$dailyAktivitas->judul}' telah dihapus oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.index'),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'removed_by' => $changedBy->id
                ]
            );
        }

        // Notify remaining assigned users about team changes
        $currentAssignedIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        $notifyUserIds = array_filter($currentAssignedIds, function($id) use ($changedBy, $addedUsers) {
            return $id !== $changedBy->id && !in_array($id, $addedUsers);
        });

        if (!empty($notifyUserIds) && (!empty($addedUsers) || !empty($removedUsers))) {
            $changeText = [];
            if (!empty($addedUsers)) {
                $addedNames = User::whereIn('id', $addedUsers)->pluck('name')->toArray();
                $changeText[] = 'Ditambahkan: ' . implode(', ', $addedNames);
            }
            if (!empty($removedUsers)) {
                $removedNames = User::whereIn('id', $removedUsers)->pluck('name')->toArray();
                $changeText[] = 'Dihapus: ' . implode(', ', $removedNames);
            }

            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Tim Aktivitas Berubah',
                "Tim aktivitas '{$dailyAktivitas->judul}' diubah oleh {$changedBy->name}. " . implode('. ', $changeText),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'changed_by' => $changedBy->id,
                    'added_users' => $addedUsers,
                    'removed_users' => $removedUsers
                ]
            );
        }
    }

    public function notifyDailyAktivitasCommentAdded($dailyAktivitas, $comment, $commentBy)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        
        // Notify assigned users (except the commenter)
        $notifyUserIds = array_filter($assignedUserIds, function($id) use ($commentBy) {
            return $id !== $commentBy->id;
        });

        if (!empty($notifyUserIds)) {
            $commentPreview = strlen($comment) > 100 ? 
                substr($comment, 0, 100) . '...' : 
                $comment;

            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Komentar Baru',
                "{$commentBy->name} menambahkan komentar pada aktivitas '{$dailyAktivitas->judul}': \"{$commentPreview}\"",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'comment_by' => $commentBy->id,
                    'comment_preview' => $commentPreview
                ]
            );
        }
    }

    /**
     * Check and send Daily Aktivitas deadline notifications (for scheduler)
     */
    public function checkDailyAktivitasDeadlines()
    {
        try {
            $now = now();
            
            // Find activities with deadlines in next 24 hours (not yet notified today)
            $upcomingActivities = \App\Models\DailyAktivitas::where('status', '!=', 'done')
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('deadline')
                ->where('deadline', '>', $now)
                ->where('deadline', '<=', $now->copy()->addHours(24))
                ->whereDoesntHave('assignedUsers', function($query) use ($now) {
                    $query->where('notified_at', '>=', $now->startOfDay());
                })
                ->get();

            foreach ($upcomingActivities as $aktivitas) {
                $hoursRemaining = $now->diffInHours($aktivitas->deadline);
                $this->notifyDailyAktivitasDeadlineApproaching($aktivitas, $hoursRemaining);
                
                // Mark as notified today
                $aktivitas->assignedUsers()->update([
                    'notified_at' => $now,
                    'notification_sent' => true
                ]);
            }

            // Find overdue activities (not yet marked as overdue today)
            $overdueActivities = \App\Models\DailyAktivitas::where('status', '!=', 'done')
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('deadline')
                ->where('deadline', '<', $now)
                ->whereDoesntHave('assignedUsers', function($query) use ($now) {
                    $query->where('notified_at', '>=', $now->startOfDay())
                          ->where('notification_sent', true);
                })
                ->get();

            foreach ($overdueActivities as $aktivitas) {
                $this->notifyDailyAktivitasOverdue($aktivitas);
                
                // Mark as notified today
                $aktivitas->assignedUsers()->update([
                    'notified_at' => $now,
                    'notification_sent' => true
                ]);
            }

            Log::info('Daily Aktivitas deadline check completed', [
                'upcoming_count' => $upcomingActivities->count(),
                'overdue_count' => $overdueActivities->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to check Daily Aktivitas deadlines', [
                'error' => $e->getMessage()
            ]);
        }
    }
}