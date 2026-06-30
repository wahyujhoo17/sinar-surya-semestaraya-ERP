<?php

/**
 * Function to format activity logs for display
 */
function formatActivityLog($log)
{
    // Ensure $detail is always an array, even if $log->detail is null or invalid JSON
    $detail = [];
    if (!empty($log->detail)) {
        $decoded = json_decode($log->detail, true);
        if (is_array($decoded)) {
            $detail = $decoded;
        }
    }

    $output = '';

    // Render khusus per modul
    if (isset($log->modul) && $log->modul === 'customer') {
        return formatCustomerActivityLog($log->aktivitas, $detail);
    }

    switch ($log->aktivitas) {
        case 'create':
            $output = 'Sales Order <span class="font-medium">' . ($detail['nomor'] ?? '-') . '</span> dibuat';
            break;
        case 'update':
            $output = 'Sales Order <span class="font-medium">' . ($detail['after']['nomor'] ?? '-') . '</span> diperbarui';
            break;
        case 'delete':
            $output = 'Sales Order <span class="font-medium">' . ($detail['nomor'] ?? '-') . '</span> dihapus';
            break;
        case 'change_status':
            $statusPembayaran = '';
            $statusPengiriman = '';

            if (isset($detail['status_pembayaran_lama']) && isset($detail['status_pembayaran_baru'])) {
                $statusPembayaranLama = statusLabel($detail['status_pembayaran_lama'] ?? '', 'payment');
                $statusPembayaranBaru = statusLabel($detail['status_pembayaran_baru'] ?? '', 'payment');
                $statusPembayaran = ' Status pembayaran diubah dari <span class="font-medium">' . $statusPembayaranLama . '</span> menjadi <span class="font-medium">' . $statusPembayaranBaru . '</span>.';
            }

            if (isset($detail['status_pengiriman_lama']) && isset($detail['status_pengiriman_baru'])) {
                $statusPengirimanLama = statusLabel($detail['status_pengiriman_lama'] ?? '', 'delivery');
                $statusPengirimanBaru = statusLabel($detail['status_pengiriman_baru'] ?? '', 'delivery');
                $statusPengiriman = ' Status pengiriman diubah dari <span class="font-medium">' . $statusPengirimanLama . '</span> menjadi <span class="font-medium">' . $statusPengirimanBaru . '</span>.';
            }

            $output = 'Status Sales Order <span class="font-medium">' . ($detail['nomor'] ?? '-') . '</span> diubah.' . $statusPembayaran . $statusPengiriman;
            break;
        case 'ubah_status':
            $output = 'Status Prospek <span class="font-medium">' . ($detail['nama_prospek'] ?? '-') . '</span> diubah dari <span class="font-medium">' . getStatusLabel($detail['status_lama'] ?? '') . '</span> menjadi <span class="font-medium">' . getStatusLabel($detail['status_baru'] ?? '') . '</span>';
            break;
        case 'export_excel':
            $output = 'Mengekspor data pipeline penjualan ke Excel. ' . ($detail['record_count'] ?? '0') . ' data diekspor';
            break;
        case 'export_csv':
            $output = 'Mengekspor data pipeline penjualan ke CSV. ' . ($detail['record_count'] ?? '0') . ' data diekspor';
            break;
        default:
            $output = 'Aktivitas: ' . ucfirst($log->aktivitas);
    }

    return $output;
}

/**
 * Helper function to get status labels for pipeline
 */
function getStatusLabel($status)
{
    $labels = [
        'baru' => 'Baru',
        'tertarik' => 'Tertarik',
        'negosiasi' => 'Negosiasi',
        'menolak' => 'Menolak',
        'menjadi_customer' => 'Menjadi Customer',
    ];

    return $labels[$status] ?? $status;
}

/**
 * Render log aktivitas khusus modul customer (pelanggan).
 *
 * @param string $aktivitas create|update|delete
 * @param array  $detail
 * @return string
 */
function formatCustomerActivityLog($aktivitas, $detail)
{
    $nama = $detail['nama'] ?? '-';
    $kode = $detail['kode'] ?? '-';

    switch ($aktivitas) {
        case 'create':
            return 'Pelanggan <span class="font-medium">' . e($nama) . '</span> '
                . '<span class="text-gray-500 dark:text-gray-400">(' . e($kode) . ')</span> ditambahkan';

        case 'delete':
            return 'Pelanggan <span class="font-medium">' . e($nama) . '</span> '
                . '<span class="text-gray-500 dark:text-gray-400">(' . e($kode) . ')</span> dihapus';

        case 'update':
            $changes = $detail['changes'] ?? [];
            if (empty($changes)) {
                return 'Pelanggan <span class="font-medium">' . e($nama) . '</span> diperbarui';
            }

            $rows = [];
            foreach ($changes as $change) {
                $label = $change['label'] ?? ucfirst(str_replace('_', ' ', ''));
                $old = trim((string)($change['old'] ?? '')) === '' ? '<em class="text-gray-400">kosong</em>' : e($change['old']);
                $new = trim((string)($change['new'] ?? '')) === '' ? '<em class="text-gray-400">kosong</em>' : e($change['new']);

                // Potong nilai yang terlalu panjang agar tampilan rapi
                if (strlen(strip_tags($old)) > 60) {
                    $old = e(\Illuminate\Support\Str::limit($change['old'], 60)) . '…';
                }
                if (strlen(strip_tags($new)) > 60) {
                    $new = e(\Illuminate\Support\Str::limit($change['new'], 60)) . '…';
                }

                $rows[] = '<li class="flex flex-col sm:flex-row sm:items-center gap-0.5 sm:gap-2">'
                    . '<span class="font-medium text-gray-700 dark:text-gray-300 min-w-[120px]">' . e($label) . '</span>'
                    . '<span class="flex flex-wrap items-center gap-1.5 text-xs">'
                    . '<span class="line-through text-gray-400 dark:text-gray-500">' . $old . '</span>'
                    . '<svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>'
                    . '<span class="text-primary-600 dark:text-primary-400 font-medium">' . $new . '</span>'
                    . '</span></li>';
            }

            return 'Pelanggan <span class="font-medium">' . e($nama) . '</span> diperbarui:'
                . '<ul class="mt-2 space-y-1.5 pl-1">' . implode('', $rows) . '</ul>';

        default:
            return 'Aktivitas pelanggan: ' . ucfirst($aktivitas);
    }
}
