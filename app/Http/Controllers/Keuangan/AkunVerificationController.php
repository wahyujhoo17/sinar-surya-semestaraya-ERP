<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\RekeningBank;
use Illuminate\Http\Request;

class AkunVerificationController extends Controller
{
    /**
     * Verifikasi konfigurasi akun rekening bank
     */
    public function verifyRekeningBankAccount()
    {
        $result = [];

        // Ambil semua akun yang terkait dengan rekening bank
        $bankAccounts = AkunAkuntansi::where('ref_type', 'App\Models\RekeningBank')
            ->orWhere('ref_type', 'App\Models\RekeningBank')
            ->get();

        foreach ($bankAccounts as $account) {
            $rekening = RekeningBank::find($account->ref_id);

            $result[] = [
                'akun_id' => $account->id,
                'akun_kode' => $account->kode,
                'akun_nama' => $account->nama,
                'akun_kategori' => $account->kategori, // Harusnya 'Aset'
                'ref_type' => $account->ref_type,
                'ref_id' => $account->ref_id,
                'rekening_nama' => $rekening ? $rekening->nama_bank . ' - ' . $rekening->nomor_rekening : 'Tidak ditemukan',
                'rekening_saldo' => $rekening ? $rekening->saldo : 'N/A',
            ];
        }

        return response()->json($result);
    }
}
