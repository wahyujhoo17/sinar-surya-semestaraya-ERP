<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\PembayaranPiutang;
use App\Models\PembayaranHutang;
use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\DB;

class TestAutomaticJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journal:test-automatic {--cleanup : Clean up test data after testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test automatic journal entry system with comprehensive scenarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Automatic Journal Entry System');
        $this->info('==========================================');

        try {
            // 1. Test Configuration
            $this->testConfiguration();

            // 2. Test Account Balance Before
            $this->testAccountBalances('BEFORE TEST');

            // 3. Create Test Transactions
            $testData = $this->createTestTransactions();

            // 4. Test Account Balance After
            $this->testAccountBalances('AFTER TEST');

            // 5. Show Journal Entries
            $this->showJournalEntries();

            // 6. Validate Journal Entry Balance
            $this->validateJournalBalance();

            // 7. Cleanup if requested
            if ($this->option('cleanup')) {
                $this->cleanupTestData($testData);
            }

            $this->info('==========================================');
            $this->info('✅ Test completed successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }

    private function testConfiguration()
    {
        $this->info('1. 🔧 Testing Account Configuration...');

        $config = config('accounting');
        if (!$config) {
            $this->error('   ❌ Accounting configuration not found!');
            return;
        }

        $requiredAccounts = [
            'kas' => 'Kas',
            'bank' => 'Bank',
            'piutang_usaha' => 'Piutang Usaha',
            'persediaan' => 'Persediaan',
            'hutang_usaha' => 'Hutang Usaha',
            'pendapatan_penjualan' => 'Pendapatan Penjualan',
            'hpp' => 'Harga Pokok Penjualan',
            'beban_operasional' => 'Beban Operasional'
        ];

        foreach ($requiredAccounts as $key => $name) {
            $accountId = $config['accounts'][$key] ?? null;
            if ($accountId) {
                $account = AkunAkuntansi::find($accountId);
                if ($account) {
                    $this->info("   ✓ {$name}: {$account->nama} (ID: {$accountId})");
                } else {
                    $this->error("   ❌ {$name}: Account ID {$accountId} not found!");
                }
            } else {
                $this->error("   ❌ {$name}: Not configured in accounting.php");
            }
        }
        $this->line('');
    }

    private function testAccountBalances($phase)
    {
        $this->info("2. 📊 Account Balances - {$phase}");

        $config = config('accounting.accounts');
        $accountIds = array_values($config);

        $accounts = AkunAkuntansi::whereIn('id', $accountIds)->get();

        foreach ($accounts as $account) {
            // Calculate balance from journal entries
            $debitTotal = JurnalUmum::where('akun_id', $account->id)->sum('debit');
            $kreditTotal = JurnalUmum::where('akun_id', $account->id)->sum('kredit');
            $balance = $debitTotal - $kreditTotal;

            $this->info(sprintf(
                "   %s: Rp %s (Debit: %s, Kredit: %s)",
                $account->nama,
                number_format($balance),
                number_format($debitTotal),
                number_format($kreditTotal)
            ));
        }
        $this->line('');
    }

    private function createTestTransactions()
    {
        $this->info('3. 🏗️  Creating Test Transactions...');

        $testData = [];

        // Create test customer
        $customer = Customer::create([
            'kode' => 'CUST-TEST-' . date('YmdHis'),
            'nama' => 'Test Customer Journal',
            'email' => 'test.journal@customer.com',
            'telepon' => '081234567890',
            'alamat' => 'Test Address for Journal',
            'is_active' => true,
        ]);
        $testData['customer'] = $customer;
        $this->info('   ✓ Customer created: ' . $customer->nama);

        // Create test supplier
        $supplier = Supplier::create([
            'kode' => 'SUPP-TEST-' . date('YmdHis'),
            'nama' => 'Test Supplier Journal',
            'email' => 'test.journal@supplier.com',
            'telepon' => '081234567890',
            'alamat' => 'Test Address for Journal',
            'is_active' => true,
        ]);
        $testData['supplier'] = $supplier;
        $this->info('   ✓ Supplier created: ' . $supplier->nama);

        // Create test sales order
        $salesOrder = SalesOrder::create([
            'nomor' => 'SO-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'customer_id' => $customer->id,
            'status' => 'approved',
            'subtotal' => 1000000,
            'diskon_nominal' => 0,
            'ppn' => 100000,
            'ongkos_kirim' => 0,
            'total' => 1100000,
            'catatan' => 'Test SO untuk jurnal otomatis',
            'user_id' => 1
        ]);
        $testData['salesOrder'] = $salesOrder;
        $this->info('   ✓ Sales Order created: ' . $salesOrder->nomor);

        // Test 1: Invoice Creation (Revenue Recording)
        $this->info('   📄 Testing Invoice creation...');
        $invoice = Invoice::create([
            'nomor' => 'INV-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'jatuh_tempo' => now()->addDays(30),
            'sales_order_id' => $salesOrder->id,
            'customer_id' => $customer->id,
            'subtotal' => 1000000,
            'diskon_nominal' => 0,
            'ppn' => 100000,
            'ongkos_kirim' => 0,
            'total' => 1100000,
            'status' => 'belum_bayar',
            'catatan' => 'Test invoice untuk jurnal otomatis',
            'user_id' => 1
        ]);
        $testData['invoice'] = $invoice;
        $this->info('      ✓ Invoice created: ' . $invoice->nomor . ' (Rp ' . number_format($invoice->total) . ')');

        // Test 2: Purchase Creation (Expense Recording)
        $this->info('   📦 Testing Purchase creation...');
        $pembelian = Pembelian::create([
            'nomor' => 'PB-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'supplier_id' => $supplier->id,
            'pr_id' => 1, // Dummy PR ID
            'subtotal' => 500000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'ppn' => 50000,
            'ongkos_kirim' => 25000,
            'total' => 575000,
            'status' => 'approved',
            'status_pembayaran' => 'belum_bayar',
            'status_penerimaan' => 'belum_diterima',
            'tanggal_pengiriman' => now()->addDays(7),
            'alamat_pengiriman' => 'Test Address Pengiriman',
            'catatan' => 'Test pembelian untuk jurnal otomatis',
            'syarat_ketentuan' => 'Test syarat ketentuan',
            'user_id' => 1
        ]);
        $testData['pembelian'] = $pembelian;
        $this->info('      ✓ Purchase created: ' . $pembelian->nomor . ' (Rp ' . number_format($pembelian->total) . ')');

        // Test 3: Payment Recording (Cash/Bank)
        $this->info('   💰 Testing Payment creation...');
        $pembayaranPiutang = PembayaranPiutang::create([
            'nomor' => 'PP-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'jumlah' => 550000, // Partial payment
            'metode_pembayaran' => 'cash',
            'no_referensi' => 'REF-' . date('YmdHis'),
            'catatan' => 'Test pembayaran piutang parsial',
            'user_id' => 1,
            'kas_id' => 1, // Default kas ID
            'rekening_bank_id' => 1 // Default bank ID
        ]);
        $testData['pembayaranPiutang'] = $pembayaranPiutang;
        $this->info('      ✓ Receivable Payment created: ' . $pembayaranPiutang->nomor . ' (Rp ' . number_format($pembayaranPiutang->jumlah) . ')');

        $pembayaranHutang = PembayaranHutang::create([
            'nomor' => 'PH-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'purchase_order_id' => $pembelian->id,
            'supplier_id' => $supplier->id,
            'jumlah' => 575000, // Full payment
            'metode_pembayaran' => 'bank',
            'rekening_id' => 1, // BRI - untuk test menggunakan rekening BRI
            'no_referensi' => 'REF-BANK-' . date('YmdHis'),
            'catatan' => 'Test pembayaran hutang penuh dengan rekening BRI',
            'user_id' => 1
        ]);
        $testData['pembayaranHutang'] = $pembayaranHutang;
        $this->info('      ✓ Payable Payment created: ' . $pembayaranHutang->nomor . ' (Rp ' . number_format($pembayaranHutang->jumlah) . ')');

        // Test 4: Operational Expense
        $this->info('   🏢 Testing Operational Expense creation...');

        // Create a test kategori_biaya first
        $kategoriBiaya = DB::table('kategori_biaya')->where('kode', 'TEST-OP')->first();

        if (!$kategoriBiaya) {
            $kategoriBiaya = DB::table('kategori_biaya')->insertGetId([
                'kode' => 'TEST-OP',
                'nama' => 'Test Operasional',
                'deskripsi' => 'Kategori test untuk jurnal otomatis',
                'akun_id' => 15, // Beban Operasional
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $kategoriBiaya = $kategoriBiaya->id;
        }

        $biayaOperasional = BiayaOperasional::create([
            'nomor' => 'BO-TEST-' . date('YmdHis'),
            'tanggal' => now(),
            'kategori_biaya_id' => $kategoriBiaya,
            'jumlah' => 200000,
            'metode_pembayaran' => 'tunai',
            'no_referensi' => 'REF-OP-' . date('YmdHis'),
            'keterangan' => 'Test biaya operasional untuk jurnal otomatis',
            'user_id' => 1,
            'bukti_transaksi' => 'bukti-test.jpg'
        ]);
        $testData['kategoriBiaya'] = $kategoriBiaya;
        $testData['biayaOperasional'] = $biayaOperasional;
        $this->info('      ✓ Operational Expense created: ' . $biayaOperasional->nomor . ' (Rp ' . number_format($biayaOperasional->jumlah) . ')');

        $this->line('');
        return $testData;
    }

    private function showJournalEntries()
    {
        $this->info('4. 📚 Journal Entries Created...');

        // Get recent journal entries (last 10)
        $journalEntries = JurnalUmum::with('akun')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        if ($journalEntries->isEmpty()) {
            $this->warn('   ⚠️  No recent journal entries found');
            return;
        }

        // Group by reference
        $groupedEntries = $journalEntries->groupBy('referensi');

        foreach ($groupedEntries as $reference => $entries) {
            $this->info("   📝 Reference: {$reference}");
            $totalDebit = 0;
            $totalKredit = 0;

            foreach ($entries as $entry) {
                $debit = $entry->debit > 0 ? 'Rp ' . number_format($entry->debit) : '-';
                $kredit = $entry->kredit > 0 ? 'Rp ' . number_format($entry->kredit) : '-';

                $this->info(sprintf(
                    "      %s | Debit: %s | Kredit: %s",
                    str_pad($entry->akun->nama, 25),
                    str_pad($debit, 15),
                    str_pad($kredit, 15)
                ));

                $totalDebit += $entry->debit;
                $totalKredit += $entry->kredit;
            }

            $balanced = $totalDebit == $totalKredit ? '✅' : '❌';
            $this->info("      " . str_repeat('-', 60));
            $this->info(sprintf(
                "      TOTAL %s | Debit: %s | Kredit: %s %s",
                str_pad('', 18),
                str_pad('Rp ' . number_format($totalDebit), 15),
                str_pad('Rp ' . number_format($totalKredit), 15),
                $balanced
            ));
            $this->line('');
        }
    }

    private function validateJournalBalance()
    {
        $this->info('5. ⚖️  Validating Journal Entry Balance...');

        // Get recent journal entries
        $journalEntries = JurnalUmum::where('created_at', '>=', now()->subMinutes(5))->get();

        if ($journalEntries->isEmpty()) {
            $this->warn('   ⚠️  No recent journal entries to validate');
            return;
        }

        // Group by reference and validate each group
        $groupedEntries = $journalEntries->groupBy('referensi');
        $allBalanced = true;

        foreach ($groupedEntries as $reference => $entries) {
            $totalDebit = $entries->sum('debit');
            $totalKredit = $entries->sum('kredit');

            if ($totalDebit == $totalKredit) {
                $this->info("   ✅ {$reference}: Balanced (Rp " . number_format($totalDebit) . ")");
            } else {
                $this->error("   ❌ {$reference}: NOT BALANCED - Debit: Rp " . number_format($totalDebit) . ", Kredit: Rp " . number_format($totalKredit));
                $allBalanced = false;
            }
        }

        if ($allBalanced) {
            $this->info('   🎉 All journal entries are properly balanced!');
        } else {
            $this->error('   ⚠️  Some journal entries are not balanced!');
        }

        $this->line('');
    }

    private function cleanupTestData($testData)
    {
        $this->info('6. 🧹 Cleaning up test data...');

        try {
            // Delete journal entries first (due to foreign key constraints)
            $recentJournals = JurnalUmum::where('created_at', '>=', now()->subMinutes(5))->get();
            foreach ($recentJournals as $journal) {
                if (str_contains($journal->referensi ?? '', 'TEST')) {
                    $journal->delete();
                }
            }
            $this->info('   ✓ Test journal entries deleted');

            // Delete test transactions in reverse order
            if (isset($testData['biayaOperasional'])) {
                $testData['biayaOperasional']->delete();
                $this->info('   ✓ Test operational expense deleted');
            }

            if (isset($testData['pembayaranHutang'])) {
                $testData['pembayaranHutang']->delete();
                $this->info('   ✓ Test payable payment deleted');
            }

            if (isset($testData['pembayaranPiutang'])) {
                $testData['pembayaranPiutang']->delete();
                $this->info('   ✓ Test receivable payment deleted');
            }

            if (isset($testData['pembelian'])) {
                $testData['pembelian']->delete();
                $this->info('   ✓ Test purchase deleted');
            }

            if (isset($testData['invoice'])) {
                $testData['invoice']->delete();
                $this->info('   ✓ Test invoice deleted');
            }

            if (isset($testData['salesOrder'])) {
                $testData['salesOrder']->delete();
                $this->info('   ✓ Test sales order deleted');
            }

            if (isset($testData['supplier'])) {
                $testData['supplier']->delete();
                $this->info('   ✓ Test supplier deleted');
            }

            if (isset($testData['customer'])) {
                $testData['customer']->delete();
                $this->info('   ✓ Test customer deleted');
            }

            if (isset($testData['kategoriBiaya'])) {
                DB::table('kategori_biaya')->where('id', $testData['kategoriBiaya'])->delete();
                $this->info('   ✓ Test kategori biaya deleted');
            }

            $this->info('   🎉 All test data cleaned up successfully!');
        } catch (\Exception $e) {
            $this->error('   ❌ Error during cleanup: ' . $e->getMessage());
        }

        $this->line('');
    }
}
