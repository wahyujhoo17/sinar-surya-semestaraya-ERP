<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\PembayaranPiutang;
use App\Models\Pembelian;
use App\Models\PembayaranHutang;
use App\Models\ReturPenjualan;
use App\Models\ReturPembelian;
use App\Models\PenyesuaianStok;
use App\Models\BiayaOperasional;
use App\Models\Penggajian;
use App\Models\JurnalUmum;
use Illuminate\Console\Command;

class TestAutomaticJournalEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journal:test {model? : The model to test (invoice, pembayaran-piutang, pembelian, pembayaran-hutang, retur-penjualan, retur-pembelian, penyesuaian-stok, biaya-operasional, penggajian)} {id? : The ID of the model to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the automatic journal entry system for various models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $id = $this->argument('id');

        if (!$model) {
            $model = $this->choice(
                'Which model do you want to test?',
                [
                    'invoice' => 'Invoice (Penjualan)',
                    'pembayaran-piutang' => 'Pembayaran Piutang',
                    'pembelian' => 'Pembelian',
                    'pembayaran-hutang' => 'Pembayaran Hutang',
                    'retur-penjualan' => 'Retur Penjualan',
                    'retur-pembelian' => 'Retur Pembelian',
                    'penyesuaian-stok' => 'Penyesuaian Stok',
                    'biaya-operasional' => 'Biaya Operasional',
                    'penggajian' => 'Penggajian',
                    'all' => 'Test All Models'
                ],
                'all'
            );
        }

        if ($model === 'all') {
            $this->testAllModels();
            return;
        }

        if (!$id && $model !== 'all') {
            $id = $this->ask('Enter the ID of the model to test');
        }

        $this->testModel($model, $id);
    }

    /**
     * Test a specific model
     *
     * @param string $model
     * @param int $id
     * @return void
     */
    private function testModel($model, $id)
    {
        $this->info("Testing automatic journal entry for {$model} with ID {$id}");

        switch ($model) {
            case 'invoice':
                $this->testInvoice($id);
                break;
            case 'pembayaran-piutang':
                $this->testPembayaranPiutang($id);
                break;
            case 'pembelian':
                $this->testPembelian($id);
                break;
            case 'pembayaran-hutang':
                $this->testPembayaranHutang($id);
                break;
            case 'retur-penjualan':
                $this->testReturPenjualan($id);
                break;
            case 'retur-pembelian':
                $this->testReturPembelian($id);
                break;
            case 'penyesuaian-stok':
                $this->testPenyesuaianStok($id);
                break;
            case 'biaya-operasional':
                $this->testBiayaOperasional($id);
                break;
            case 'penggajian':
                $this->testPenggajian($id);
                break;
            default:
                $this->error("Unknown model: {$model}");
                break;
        }
    }

    /**
     * Test all models
     *
     * @return void
     */
    private function testAllModels()
    {
        $this->info('Testing all models...');

        // Test Invoice
        $invoice = Invoice::orderBy('id', 'desc')->first();
        if ($invoice) {
            $this->testInvoice($invoice->id);
        } else {
            $this->warn('No Invoice found to test');
        }

        // Test PembayaranPiutang
        $pembayaranPiutang = PembayaranPiutang::orderBy('id', 'desc')->first();
        if ($pembayaranPiutang) {
            $this->testPembayaranPiutang($pembayaranPiutang->id);
        } else {
            $this->warn('No PembayaranPiutang found to test');
        }

        // Test Pembelian
        $pembelian = Pembelian::orderBy('id', 'desc')->first();
        if ($pembelian) {
            $this->testPembelian($pembelian->id);
        } else {
            $this->warn('No Pembelian found to test');
        }

        // Test PembayaranHutang
        $pembayaranHutang = PembayaranHutang::orderBy('id', 'desc')->first();
        if ($pembayaranHutang) {
            $this->testPembayaranHutang($pembayaranHutang->id);
        } else {
            $this->warn('No PembayaranHutang found to test');
        }

        // Test ReturPenjualan
        $returPenjualan = ReturPenjualan::orderBy('id', 'desc')->first();
        if ($returPenjualan) {
            $this->testReturPenjualan($returPenjualan->id);
        } else {
            $this->warn('No ReturPenjualan found to test');
        }

        // Test ReturPembelian
        $returPembelian = ReturPembelian::orderBy('id', 'desc')->first();
        if ($returPembelian) {
            $this->testReturPembelian($returPembelian->id);
        } else {
            $this->warn('No ReturPembelian found to test');
        }

        // Test PenyesuaianStok
        $penyesuaianStok = PenyesuaianStok::orderBy('id', 'desc')->first();
        if ($penyesuaianStok) {
            $this->testPenyesuaianStok($penyesuaianStok->id);
        } else {
            $this->warn('No PenyesuaianStok found to test');
        }

        // Test BiayaOperasional
        $biayaOperasional = BiayaOperasional::orderBy('id', 'desc')->first();
        if ($biayaOperasional) {
            $this->testBiayaOperasional($biayaOperasional->id);
        } else {
            $this->warn('No BiayaOperasional found to test');
        }

        // Test Penggajian
        $penggajian = Penggajian::orderBy('id', 'desc')->first();
        if ($penggajian) {
            $this->testPenggajian($penggajian->id);
        } else {
            $this->warn('No Penggajian found to test');
        }
    }

    /**
     * Test Invoice journal entry
     *
     * @param int $id
     * @return void
     */
    private function testInvoice($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            $this->error("Invoice with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\Invoice', $id);
    }

    /**
     * Test PembayaranPiutang journal entry
     *
     * @param int $id
     * @return void
     */
    private function testPembayaranPiutang($id)
    {
        $pembayaranPiutang = PembayaranPiutang::find($id);
        if (!$pembayaranPiutang) {
            $this->error("PembayaranPiutang with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\PembayaranPiutang', $id);
    }

    /**
     * Test Pembelian journal entry
     *
     * @param int $id
     * @return void
     */
    private function testPembelian($id)
    {
        $pembelian = Pembelian::find($id);
        if (!$pembelian) {
            $this->error("Pembelian with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\Pembelian', $id);
    }

    /**
     * Test PembayaranHutang journal entry
     *
     * @param int $id
     * @return void
     */
    private function testPembayaranHutang($id)
    {
        $pembayaranHutang = PembayaranHutang::find($id);
        if (!$pembayaranHutang) {
            $this->error("PembayaranHutang with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\PembayaranHutang', $id);
    }

    /**
     * Test ReturPenjualan journal entry
     *
     * @param int $id
     * @return void
     */
    private function testReturPenjualan($id)
    {
        $returPenjualan = ReturPenjualan::find($id);
        if (!$returPenjualan) {
            $this->error("ReturPenjualan with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\ReturPenjualan', $id);
    }

    /**
     * Test ReturPembelian journal entry
     *
     * @param int $id
     * @return void
     */
    private function testReturPembelian($id)
    {
        $returPembelian = ReturPembelian::find($id);
        if (!$returPembelian) {
            $this->error("ReturPembelian with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\ReturPembelian', $id);
    }

    /**
     * Test PenyesuaianStok journal entry
     *
     * @param int $id
     * @return void
     */
    private function testPenyesuaianStok($id)
    {
        $penyesuaianStok = PenyesuaianStok::find($id);
        if (!$penyesuaianStok) {
            $this->error("PenyesuaianStok with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\PenyesuaianStok', $id);
    }

    /**
     * Test BiayaOperasional journal entry
     *
     * @param int $id
     * @return void
     */
    private function testBiayaOperasional($id)
    {
        $biayaOperasional = BiayaOperasional::find($id);
        if (!$biayaOperasional) {
            $this->error("BiayaOperasional with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\BiayaOperasional', $id);
    }

    /**
     * Test Penggajian journal entry
     *
     * @param int $id
     * @return void
     */
    private function testPenggajian($id)
    {
        $penggajian = Penggajian::find($id);
        if (!$penggajian) {
            $this->error("Penggajian with ID {$id} not found");
            return;
        }

        $this->checkJournalEntries('App\Models\Penggajian', $id);
    }

    /**
     * Check journal entries for a specific model
     *
     * @param string $refType
     * @param int $refId
     * @return void
     */
    private function checkJournalEntries($refType, $refId)
    {
        $journals = JurnalUmum::where('ref_type', $refType)
            ->where('ref_id', $refId)
            ->get();

        if ($journals->isEmpty()) {
            $this->error("No journal entries found for {$refType} with ID {$refId}");
            return;
        }

        $this->info("Found " . $journals->count() . " journal entries");

        $totalDebit = $journals->sum('debit');
        $totalKredit = $journals->sum('kredit');

        $this->table(
            ['No Referensi', 'Tanggal', 'Keterangan', 'Akun', 'Debit', 'Kredit'],
            $journals->map(function ($journal) {
                return [
                    'no_referensi' => $journal->no_referensi,
                    'tanggal' => $journal->tanggal,
                    'keterangan' => $journal->keterangan,
                    'akun' => $journal->akun->kode . ' - ' . $journal->akun->nama,
                    'debit' => number_format($journal->debit, 2),
                    'kredit' => number_format($journal->kredit, 2),
                ];
            })
        );

        $this->info("Total Debit: " . number_format($totalDebit, 2));
        $this->info("Total Kredit: " . number_format($totalKredit, 2));

        if (abs($totalDebit - $totalKredit) < 0.01) {
            $this->info("✅ Journal is balanced");
        } else {
            $this->error("❌ Journal is not balanced! Difference: " . number_format(abs($totalDebit - $totalKredit), 2));
        }
    }
}
