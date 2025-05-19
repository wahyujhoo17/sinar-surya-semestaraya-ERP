<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backup the original file
        $controllerPath = app_path('Http/Controllers/Keuangan/PembayaranHutangController.php');
        $backupPath = app_path('Http/Controllers/Keuangan/PembayaranHutangController.php.bak');

        if (File::exists($controllerPath)) {
            // Create a backup
            File::copy($controllerPath, $backupPath);

            // Get the file content
            $content = File::get($controllerPath);

            // Replace all instances of the PO status update logic
            $pattern = '/\$sisaHutang = \$po->total - \$totalPayments - \$totalReturValue;\s+\/\/ Update PO status\s+if \(\$sisaHutang <= 0\) {\s+\$po->status_pembayaran = \'lunas\';\s+} else {\s+\$po->status_pembayaran = \'sebagian\';\s+}/';

            $replacement = '$sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Update PO status
            if ($sisaHutang < 0) {
                // Kelebihan bayar situation
                $po->status_pembayaran = \'kelebihan_bayar\';
                $po->kelebihan_bayar = abs($sisaHutang);
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = \'lunas\';
                $po->kelebihan_bayar = 0;
            } else {
                $po->status_pembayaran = \'sebagian\';
                $po->kelebihan_bayar = 0;
            }';

            $newContent = preg_replace($pattern, $replacement, $content);

            // Save the file
            File::put($controllerPath, $newContent);

            // Also update any instance that might have different indentation
            $pattern2 = '/\$sisaHutang = \$po->total - \$totalPayments - \$totalReturValue;\s+\/\/ Update PO status\s+if \(\$sisaHutang <= 0\) {\s+\$po->status_pembayaran = \'lunas\';\s+} elseif \(\$totalPayments > 0\) {\s+\$po->status_pembayaran = \'sebagian\';\s+} else {\s+\$po->status_pembayaran = \'belum_bayar\';\s+}/';

            $replacement2 = '$sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Update PO status
            if ($sisaHutang < 0) {
                // Kelebihan bayar situation
                $po->status_pembayaran = \'kelebihan_bayar\';
                $po->kelebihan_bayar = abs($sisaHutang);
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = \'lunas\';
                $po->kelebihan_bayar = 0;
            } else if ($totalPayments > 0) {
                $po->status_pembayaran = \'sebagian\';
                $po->kelebihan_bayar = 0;
            } else {
                $po->status_pembayaran = \'belum_bayar\';
                $po->kelebihan_bayar = 0;
            }';

            $content = File::get($controllerPath);
            $newContent = preg_replace($pattern2, $replacement2, $content);
            File::put($controllerPath, $newContent);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
