<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AkunAkuntansi;
use Illuminate\Support\Facades\DB;

class CreateMissingAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing accounts for automatic journal entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating missing accounts for automatic journal entries...');

        DB::beginTransaction();

        try {
            // Find parent accounts
            $asetLancar = AkunAkuntansi::where('kode', '1100')->first();
            $kewajiban = AkunAkuntansi::where('kode', '2100')->first();
            $beban = AkunAkuntansi::where('kode', '5000')->first();
            $bebanOperasional = AkunAkuntansi::where('kode', '5200')->first();

            if (!$asetLancar || !$kewajiban || !$beban) {
                $this->error('Parent accounts not found. Please ensure basic chart of accounts is set up.');
                return 1;
            }

            // Define accounts to create
            $accountsToCreate = [
                [
                    'kode' => '1110',
                    'nama' => 'Piutang Usaha',
                    'kategori' => 'asset',
                    'tipe' => 'detail',
                    'parent_id' => $asetLancar->id,
                ],
                [
                    'kode' => '1120',
                    'nama' => 'Persediaan Barang Dagang',
                    'kategori' => 'asset',
                    'tipe' => 'detail',
                    'parent_id' => $asetLancar->id,
                ],
                [
                    'kode' => '1130',
                    'nama' => 'PPN Masukan',
                    'kategori' => 'asset',
                    'tipe' => 'detail',
                    'parent_id' => $asetLancar->id,
                ],
                [
                    'kode' => '2120',
                    'nama' => 'PPN Keluaran',
                    'kategori' => 'liability',
                    'tipe' => 'detail',
                    'parent_id' => $kewajiban->id,
                ],
                [
                    'kode' => '5100',
                    'nama' => 'Harga Pokok Penjualan',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $beban->id,
                ],
                [
                    'kode' => '5213',
                    'nama' => 'Beban Sewa',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $bebanOperasional ? $bebanOperasional->id : $beban->id,
                ],
                [
                    'kode' => '5214',
                    'nama' => 'Beban Administrasi',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $bebanOperasional ? $bebanOperasional->id : $beban->id,
                ],
                [
                    'kode' => '5215',
                    'nama' => 'Beban Transportasi',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $bebanOperasional ? $bebanOperasional->id : $beban->id,
                ],
                [
                    'kode' => '5216',
                    'nama' => 'Beban Lainnya',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $bebanOperasional ? $bebanOperasional->id : $beban->id,
                ],
                [
                    'kode' => '5300',
                    'nama' => 'Penyesuaian Persediaan',
                    'kategori' => 'expense',
                    'tipe' => 'detail',
                    'parent_id' => $beban->id,
                ],
            ];

            foreach ($accountsToCreate as $accountData) {
                // Check if account already exists
                $existing = AkunAkuntansi::where('kode', $accountData['kode'])
                    ->orWhere('nama', $accountData['nama'])
                    ->first();

                if ($existing) {
                    $this->warn("Account {$accountData['nama']} already exists (ID: {$existing->id})");
                    continue;
                }

                // Create new account
                $account = AkunAkuntansi::create([
                    'kode' => $accountData['kode'],
                    'nama' => $accountData['nama'],
                    'kategori' => $accountData['kategori'],
                    'tipe' => $accountData['tipe'],
                    'parent_id' => $accountData['parent_id'],
                    'is_active' => true,
                ]);

                $this->info("✓ Created: {$account->nama} (ID: {$account->id}, Kode: {$account->kode})");
            }

            DB::commit();

            $this->info('');
            $this->info('All missing accounts have been created successfully!');
            $this->info('You can now update your .env file with the correct account IDs.');

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error creating accounts: ' . $e->getMessage());
            return 1;
        }
    }
}
