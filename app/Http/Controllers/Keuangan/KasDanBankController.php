<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KasDanBankController extends Controller
{
    /**
     * Menampilkan halaman manajemen kas dan bank
     */
    public function index()
    {
        // Mengambil data kas dan rekening bank yang aktif
        $kasAll = Kas::where('is_aktif', true)->get();
        $rekeningAll = RekeningBank::where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->get();

        // Hitung total saldo kas dan rekening
        $totalKas = $kasAll->sum('saldo');
        $totalRekening = $rekeningAll->sum('saldo');

        // Data transaksi terbaru (5 transaksi terakhir)
        $transaksiKas = TransaksiKas::with('kas', 'user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $transaksiBank = TransaksiBank::with('rekening', 'user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Breadcrumbs untuk navigasi
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.index', compact(
            'kasAll',
            'rekeningAll',
            'totalKas',
            'totalRekening',
            'transaksiKas',
            'transaksiBank',
            'breadcrumbs'
        ))->with('currentPage', 'Kas & Bank');
    }

    /**
     * Menampilkan detail kas
     */
    public function detailKas($id)
    {
        $kas = Kas::findOrFail($id);
        $transaksi = TransaksiKas::where('kas_id', $id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => route('keuangan.kas-dan-bank.index')],
            ['name' => 'Detail Kas: ' . $kas->nama, 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.detail_kas', compact(
            'kas',
            'transaksi',
            'breadcrumbs'
        ))->with('currentPage', 'Detail Kas');
    }

    /**
     * Menampilkan detail rekening bank
     */
    public function detailRekening($id)
    {
        $rekening = RekeningBank::findOrFail($id);
        $transaksi = TransaksiBank::where('rekening_id', $id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => route('keuangan.kas-dan-bank.index')],
            ['name' => 'Detail Rekening: ' . $rekening->nama_bank, 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.detail_rekening', compact(
            'rekening',
            'transaksi',
            'breadcrumbs'
        ))->with('currentPage', 'Detail Rekening');
    }

    /**
     * Menyimpan kas baru
     */
    public function storeKas(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'saldo' => 'required|numeric|min:0',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buat kas baru
            $kas = Kas::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'saldo' => $request->saldo,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            // Jika saldo awal > 0, buat transaksi kas masuk untuk saldo awal
            if ($request->saldo > 0) {
                TransaksiKas::create([
                    'kas_id' => $kas->id,
                    'tanggal' => now(),
                    'keterangan' => 'Saldo awal kas ' . $kas->nama,
                    'jumlah' => $request->saldo,
                    'jenis' => 'masuk',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kas berhasil ditambahkan',
                'data' => $kas
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data kas
     */
    public function updateKas(Request $request, $id)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update kas
            $kas = Kas::findOrFail($id);
            $kas->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kas berhasil diperbarui',
                'data' => $kas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus kas
     */
    public function destroyKas($id)
    {
        try {
            $kas = Kas::findOrFail($id);

            // Cek apakah kas memiliki transaksi
            $hasTransactions = TransaksiKas::where('kas_id', $id)->exists();

            if ($hasTransactions) {
                // Jika ada transaksi, nonaktifkan saja
                $kas->update(['is_aktif' => false]);
                $message = 'Kas dinonaktifkan karena memiliki riwayat transaksi';
            } else {
                // Jika tidak ada transaksi, hapus permanen
                $kas->delete();
                $message = 'Kas berhasil dihapus';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan rekening bank baru
     */
    public function storeRekening(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
            'cabang' => 'nullable|string|max:255',
            'saldo' => 'required|numeric|min:0',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buat rekening baru
            $rekening = RekeningBank::create([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'cabang' => $request->cabang,
                'saldo' => $request->saldo,
                'is_aktif' => $request->is_aktif ? true : false,
                'is_perusahaan' => true, // Selalu set true karena ini rekening perusahaan
            ]);

            // Jika saldo awal > 0, buat transaksi bank masuk untuk saldo awal
            if ($request->saldo > 0) {
                TransaksiBank::create([
                    'rekening_id' => $rekening->id,
                    'tanggal' => now(),
                    'keterangan' => 'Saldo awal rekening ' . $rekening->nama_bank . ' - ' . $rekening->nomor_rekening,
                    'jumlah' => $request->saldo,
                    'jenis' => 'masuk',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil ditambahkan',
                'data' => $rekening
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data rekening bank
     */
    public function updateRekening(Request $request, $id)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
            'cabang' => 'nullable|string|max:255',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update rekening bank
            $rekening = RekeningBank::findOrFail($id);
            $rekening->update([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'cabang' => $request->cabang,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil diperbarui',
                'data' => $rekening
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus rekening bank
     */
    public function destroyRekening($id)
    {
        try {
            $rekening = RekeningBank::findOrFail($id);

            // Cek apakah rekening memiliki transaksi
            $hasTransactions = TransaksiBank::where('rekening_id', $id)->exists();

            if ($hasTransactions) {
                // Jika ada transaksi, nonaktifkan saja
                $rekening->update(['is_aktif' => false]);
                $message = 'Rekening dinonaktifkan karena memiliki riwayat transaksi';
            } else {
                // Jika tidak ada transaksi, hapus permanen
                $rekening->delete();
                $message = 'Rekening bank berhasil dihapus';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
