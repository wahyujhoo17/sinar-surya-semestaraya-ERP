<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\AkunAkuntansi;
use Illuminate\Support\Facades\Log;

class AccountingConfigValidator
{
    /**
     * Validate that all required accounting accounts are configured
     *
     * @return array
     */
    public function validateConfig()
    {
        $missingAccounts = [];
        $accountsToCheck = $this->getRequiredAccounts();

        foreach ($accountsToCheck as $key => $accountName) {
            $accountId = config("accounting.{$key}");

            if (!$accountId) {
                $missingAccounts[] = [
                    'key' => $key,
                    'name' => $accountName,
                    'status' => 'Not Configured'
                ];
            } else {
                $account = AkunAkuntansi::find($accountId);

                if (!$account) {
                    $missingAccounts[] = [
                        'key' => $key,
                        'name' => $accountName,
                        'status' => 'Invalid Account ID'
                    ];
                }
            }
        }

        return $missingAccounts;
    }

    /**
     * Get an array of required accounts to check
     *
     * @return array
     */
    public function getRequiredAccounts()
    {
        return [
            // Penjualan
            'penjualan.piutang_usaha' => 'Piutang Usaha',
            'penjualan.pendapatan_penjualan' => 'Pendapatan Penjualan',
            'penjualan.ppn_keluaran' => 'PPN Keluaran',
            'penjualan.hpp' => 'Harga Pokok Penjualan',
            'penjualan.persediaan' => 'Persediaan Barang',

            // Pembelian
            'pembelian.hutang_usaha' => 'Hutang Usaha',
            'pembelian.persediaan' => 'Persediaan Barang',
            'pembelian.ppn_masukan' => 'PPN Masukan',

            // Pembayaran Piutang
            'pembayaran_piutang.kas' => 'Kas',
            'pembayaran_piutang.bank' => 'Bank',
            'pembayaran_piutang.piutang_usaha' => 'Piutang Usaha',

            // Pembayaran Hutang
            'pembayaran_hutang.kas' => 'Kas',
            'pembayaran_hutang.bank' => 'Bank',
            'pembayaran_hutang.hutang_usaha' => 'Hutang Usaha',

            // Retur Penjualan
            'retur_penjualan.piutang_usaha' => 'Piutang Usaha',
            'retur_penjualan.pendapatan_penjualan' => 'Pendapatan Penjualan',
            'retur_penjualan.ppn_keluaran' => 'PPN Keluaran',
            'retur_penjualan.persediaan' => 'Persediaan Barang',
            'retur_penjualan.hpp' => 'Harga Pokok Penjualan',

            // Retur Pembelian
            'retur_pembelian.hutang_usaha' => 'Hutang Usaha',
            'retur_pembelian.persediaan' => 'Persediaan Barang',
            'retur_pembelian.ppn_masukan' => 'PPN Masukan',

            // Beban Operasional (minimal)
            'beban_operasional.kas' => 'Kas',
            'beban_operasional.bank' => 'Bank',
            'beban_operasional.beban_lainnya' => 'Beban Lainnya',

            // Penyesuaian Stok
            'penyesuaian_stok.persediaan' => 'Persediaan Barang',
            'penyesuaian_stok.penyesuaian_persediaan' => 'Penyesuaian Persediaan',
        ];
    }

    /**
     * Check if all required accounts for a specific transaction type are configured
     *
     * @param string $transactionType Type of transaction (e.g., 'penjualan', 'pembelian')
     * @return bool
     */
    public function validateTransactionConfig($transactionType)
    {
        $allAccounts = $this->getRequiredAccounts();
        $requiredAccounts = [];

        // Filter accounts for the specific transaction type
        foreach ($allAccounts as $key => $name) {
            if (strpos($key, $transactionType . '.') === 0) {
                $requiredAccounts[$key] = $name;
            }
        }

        // Check each required account
        foreach ($requiredAccounts as $key => $name) {
            $accountId = config($key);

            if (!$accountId || !AkunAkuntansi::find($accountId)) {
                Log::warning("Missing required accounting account for {$transactionType}: {$name}");
                return false;
            }
        }

        return true;
    }
}
