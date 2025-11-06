<?php

/**
 * Script untuk mencari Invoice dengan DP (Uang Muka)
 * Jalankan dari root project: php find_invoice_with_dp.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Invoice;

echo "==========================================\n";
echo "MENCARI INVOICE DENGAN DP (UANG MUKA)\n";
echo "==========================================\n\n";

// Cari invoice yang memiliki uang_muka_terapkan > 0
$invoicesWithDP = Invoice::where('uang_muka_terapkan', '>', 0)
    ->with(['customer', 'salesOrder'])
    ->orderBy('created_at', 'desc')
    ->get();

if ($invoicesWithDP->isEmpty()) {
    echo "❌ Tidak ada invoice dengan DP yang diterapkan.\n\n";

    echo "Untuk membuat invoice dengan DP, Anda bisa:\n";
    echo "1. Buat Uang Muka Penjualan terlebih dahulu\n";
    echo "2. Saat membuat/edit Invoice, aplikasikan Uang Muka tersebut\n\n";

    // Contoh kode untuk membuat invoice dengan DP
    echo "CONTOH KODE UNTUK MEMBUAT INVOICE DENGAN DP:\n";
    echo "==========================================\n";
    echo <<<'CODE'
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\UangMukaPenjualan;
use App\Models\UangMukaAplikasi;
use App\Models\SalesOrder;
use App\Models\Customer;

// 1. Pastikan ada Uang Muka Penjualan yang tersedia
$uangMuka = UangMukaPenjualan::create([
    'nomor' => 'UM-' . date('Ymd') . '-001',
    'tanggal' => now(),
    'customer_id' => 1, // ID customer Anda
    'sales_order_id' => 1, // ID sales order
    'jumlah' => 1000000, // Jumlah uang muka (misal 1 juta)
    'jumlah_terpakai' => 0,
    'jumlah_tersedia' => 1000000,
    'status' => 'tersedia',
    'metode_pembayaran' => 'transfer',
    'keterangan' => 'DP 30% untuk PO-123456',
    'periode_id' => 1,
    'user_id' => 1
]);

// 2. Buat Invoice
$invoice = Invoice::create([
    'nomor' => 'INV-' . date('Ymd') . '-001',
    'tanggal' => now(),
    'customer_id' => 1,
    'sales_order_id' => 1,
    'subtotal' => 3000000,
    'diskon_nominal' => 0,
    'ppn' => 330000, // 11% dari 3jt
    'ongkos_kirim' => 0,
    'total' => 3330000,
    'uang_muka_terapkan' => 1000000, // DP yang diterapkan
    'sisa_tagihan' => 2330000, // Total - DP
    'status' => 'sebagian',
    'periode_id' => 1,
    'user_id' => 1
]);

// 3. Buat Detail Invoice
InvoiceDetail::create([
    'invoice_id' => $invoice->id,
    'produk_id' => 1,
    'quantity' => 10,
    'harga' => 300000,
    'diskon_persen' => 0,
    'diskon_nominal' => 0,
    'subtotal' => 3000000
]);

// 4. Aplikasikan Uang Muka ke Invoice
UangMukaAplikasi::create([
    'uang_muka_id' => $uangMuka->id,
    'invoice_id' => $invoice->id,
    'jumlah' => 1000000, // Jumlah yang diaplikasikan
    'tanggal' => now(),
    'periode_id' => 1,
    'user_id' => 1
]);

// 5. Update Uang Muka
$uangMuka->jumlah_terpakai = 1000000;
$uangMuka->jumlah_tersedia = 0;
$uangMuka->status = 'terpakai';
$uangMuka->save();

echo "✅ Invoice dengan DP berhasil dibuat!\n";
echo "Nomor Invoice: {$invoice->nomor}\n";
echo "Total Invoice: Rp " . number_format($invoice->total, 0, ',', '.') . "\n";
echo "DP Diterapkan: Rp " . number_format($invoice->uang_muka_terapkan, 0, ',', '.') . "\n";
echo "Sisa Tagihan: Rp " . number_format($invoice->sisa_tagihan, 0, ',', '.') . "\n";

CODE;
} else {
    echo "✅ Ditemukan " . $invoicesWithDP->count() . " invoice dengan DP:\n\n";

    foreach ($invoicesWithDP as $index => $invoice) {
        echo "--- Invoice #" . ($index + 1) . " ---\n";
        echo "ID: " . $invoice->id . "\n";
        echo "Nomor: " . $invoice->nomor . "\n";
        echo "Tanggal: " . \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') . "\n";
        echo "Customer: " . ($invoice->customer->company ?? $invoice->customer->nama) . "\n";

        if ($invoice->salesOrder) {
            echo "Nomor PO: " . ($invoice->salesOrder->nomor_po ?? '-') . "\n";
            echo "Total PO: Rp " . number_format($invoice->salesOrder->total ?? 0, 0, ',', '.') . "\n";
        }

        echo "Subtotal: Rp " . number_format($invoice->subtotal, 0, ',', '.') . "\n";

        if ($invoice->ppn > 0) {
            echo "PPN: Rp " . number_format($invoice->ppn, 0, ',', '.') . "\n";
        }

        if ($invoice->ongkos_kirim > 0) {
            echo "Ongkos Kirim: Rp " . number_format($invoice->ongkos_kirim, 0, ',', '.') . "\n";
        }

        echo "Total Invoice: Rp " . number_format($invoice->total, 0, ',', '.') . "\n";
        echo "DP Diterapkan: Rp " . number_format($invoice->uang_muka_terapkan, 0, ',', '.') . "\n";

        // Hitung persentase DP
        $totalPO = $invoice->salesOrder->total ?? $invoice->total;
        $persenDP = $totalPO > 0 ? round(($invoice->uang_muka_terapkan / $totalPO) * 100, 2) : 0;
        echo "Persentase DP: " . $persenDP . "%\n";

        echo "Sisa Tagihan: Rp " . number_format($invoice->sisa_tagihan ?? 0, 0, ',', '.') . "\n";
        echo "Status: " . $invoice->status . "\n";
        echo "\n";
    }

    // Tampilkan kode untuk mengakses invoice pertama
    if ($invoicesWithDP->isNotEmpty()) {
        $firstInvoice = $invoicesWithDP->first();
        echo "==========================================\n";
        echo "KODE UNTUK MENGAKSES INVOICE PERTAMA:\n";
        echo "==========================================\n";
        echo <<<CODE
use App\Models\Invoice;

\$invoice = Invoice::with(['customer', 'salesOrder', 'details.produk'])
    ->find({$firstInvoice->id});

// Informasi Invoice
echo "Nomor Invoice: " . \$invoice->nomor . "\\n";
echo "Customer: " . (\$invoice->customer->company ?? \$invoice->customer->nama) . "\\n";
echo "Total: Rp " . number_format(\$invoice->total, 0, ',', '.') . "\\n";
echo "DP Diterapkan: Rp " . number_format(\$invoice->uang_muka_terapkan, 0, ',', '.') . "\\n";
echo "Sisa Tagihan: Rp " . number_format(\$invoice->sisa_tagihan, 0, ',', '.') . "\\n";

// Generate PDF dengan DP
use App\Services\PDFInvoiceTamplate;
\$pdfService = new PDFInvoiceTamplate();
\$pdf = \$pdfService->fillInvoiceTemplate(\$invoice);
\$pdf->Output('invoice_with_dp.pdf', 'I'); // I = inline, D = download

CODE;
    }
}

echo "\n==========================================\n";
echo "SELESAI\n";
echo "==========================================\n";
