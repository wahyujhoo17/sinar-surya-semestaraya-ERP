<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Services\AccountingConfigValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class KonfigurasiAkunController extends Controller
{
    /**
     * Display the accounting configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $validator = new AccountingConfigValidator();
        $missingAccounts = $validator->validateConfig();

        // Get all accounts for dropdowns
        $accounts = AkunAkuntansi::orderBy('kode')
            ->orderBy('nama')
            ->get();

        // Get current configuration
        $currentConfig = [];
        $envConfig = [];

        // Group configurations by transaction type
        $configByType = [
            'penjualan' => [],
            'pembelian' => [],
            'pembayaran_piutang' => [],
            'pembayaran_hutang' => [],
            'retur_penjualan' => [],
            'retur_pembelian' => [],
            'beban_operasional' => [],
            'penyesuaian_stok' => []
        ];

        // Get all account names from validator
        $accountNames = $validator->getRequiredAccounts();

        // Get current configuration values
        foreach ($accountNames as $key => $name) {
            list($type, $account) = explode('.', $key);

            $configKey = "accounting.{$key}";
            $envKey = strtoupper("AKUN_" . $account . "_ID");

            $configValue = config($configKey);

            $currentConfig[$key] = $configValue;
            $envConfig[$key] = [
                'env_key' => $envKey,
                'value' => $configValue
            ];

            // Add to grouped config
            $configByType[$type][$account] = [
                'name' => $name,
                'key' => $key,
                'env_key' => $envKey,
                'value' => $configValue,
                'account' => $configValue ? AkunAkuntansi::find($configValue) : null
            ];
        }

        return view('akuntansi.konfigurasi.index', compact(
            'missingAccounts',
            'accounts',
            'currentConfig',
            'envConfig',
            'configByType'
        ));
    }

    /**
     * Update the accounting configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = new AccountingConfigValidator();
        $accountNames = $validator->getRequiredAccounts();

        $updates = [];

        foreach ($accountNames as $key => $name) {
            list($type, $account) = explode('.', $key);
            $envKey = strtoupper("AKUN_" . $account . "_ID");

            if ($request->has("config.{$key}")) {
                $value = $request->input("config.{$key}");

                // Only update if the value has changed
                if ($value != config("accounting.{$key}")) {
                    $updates[$envKey] = $value;
                }
            }
        }

        if (!empty($updates)) {
            $this->updateEnvFile($updates);

            // Clear config cache
            Artisan::call('config:clear');
        }

        return redirect()->route('akuntansi.konfigurasi.index')
            ->with('success', 'Konfigurasi akun akuntansi berhasil diperbarui');
    }

    /**
     * Update the .env file with new values.
     *
     * @param  array  $updates
     * @return bool
     */
    private function updateEnvFile($updates)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $envContent = File::get($envPath);

        foreach ($updates as $key => $value) {
            // Check if the key exists in the .env file
            if (strpos($envContent, $key . '=') !== false) {
                // Replace existing value
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                // Add new key-value pair
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);

        return true;
    }
}
