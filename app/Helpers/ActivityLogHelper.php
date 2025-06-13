<?php

/**
 * Function to format activity logs for display
 */
function formatActivityLog($log)
{
    $detail = json_decode($log->detail, true);
    $output = '';

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
                $statusPembayaranLama = statusLabel($detail['status_pembayaran_lama'], 'payment');
                $statusPembayaranBaru = statusLabel($detail['status_pembayaran_baru'], 'payment');
                $statusPembayaran = ' Status pembayaran diubah dari <span class="font-medium">' . $statusPembayaranLama . '</span> menjadi <span class="font-medium">' . $statusPembayaranBaru . '</span>.';
            }

            if (isset($detail['status_pengiriman_lama']) && isset($detail['status_pengiriman_baru'])) {
                $statusPengirimanLama = statusLabel($detail['status_pengiriman_lama'], 'delivery');
                $statusPengirimanBaru = statusLabel($detail['status_pengiriman_baru'], 'delivery');
                $statusPengiriman = ' Status pengiriman diubah dari <span class="font-medium">' . $statusPengirimanLama . '</span> menjadi <span class="font-medium">' . $statusPengirimanBaru . '</span>.';
            }

            $output = 'Status Sales Order <span class="font-medium">' . ($detail['nomor'] ?? '-') . '</span> diubah.' . $statusPembayaran . $statusPengiriman;
            break;
        case 'ubah_status':
            $output = 'Status Prospek <span class="font-medium">' . ($detail['nama_prospek'] ?? '-') . '</span> diubah dari <span class="font-medium">' . getStatusLabel($detail['status_lama']) . '</span> menjadi <span class="font-medium">' . getStatusLabel($detail['status_baru']) . '</span>';
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
