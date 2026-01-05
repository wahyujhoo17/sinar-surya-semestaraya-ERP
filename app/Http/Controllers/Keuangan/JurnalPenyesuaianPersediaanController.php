<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\AccountingConfiguration;
use App\Models\StokProduk;
use App\Models\Produk;
use App\Models\JurnalUmum;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JurnalPenyesuaianPersediaanController extends Controller
{
    /**
     * Halaman kalibrasi persediaan
     * Menampilkan perbandingan antara nilai persediaan di akuntansi vs nilai fisik
     */
    public function index()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Keuangan', 'url' => '#'],
            ['label' => 'Kalibrasi Persediaan', 'url' => null],
        ];

        // Ambil akun persediaan dari konfigurasi penyesuaian_stok
        $persediaanConfig = AccountingConfiguration::where('transaction_type', 'penyesuaian_stok')
            ->where('account_key', 'persediaan')
            ->first();

        $akunPersediaan = null;
        if ($persediaanConfig && $persediaanConfig->akun_id) {
            $akunPersediaan = AkunAkuntansi::find($persediaanConfig->akun_id);
        }

        // Jika tidak ada konfigurasi, cari akun persediaan berdasarkan kode 1120
        if (!$akunPersediaan) {
            $akunPersediaan = AkunAkuntansi::where('kode', 'LIKE', '1120%')
                ->where('kategori', 'asset')
                ->where('is_active', true)
                ->first();
        }

        // Hitung nilai persediaan dari jurnal (accounting)
        $nilaiPersediaanAkuntansi = 0;
        if ($akunPersediaan) {
            $nilaiPersediaanAkuntansi = JurnalUmum::where('akun_id', $akunPersediaan->id)
                ->where('is_posted', true)
                ->sum(DB::raw('debit - kredit'));
        }

        // Hitung nilai persediaan fisik dari stok gudang
        $nilaiPersediaanFisik = $this->calculatePhysicalInventoryValue();

        // Selisih
        $selisih = $nilaiPersediaanFisik - $nilaiPersediaanAkuntansi;

        // Ambil detail per produk
        $detailProduk = $this->getInventoryDetails();

        // Ambil daftar gudang
        $gudangs = Gudang::where('is_active', true)->get();

        // Cek produk tanpa harga
        $produkTanpaHarga = StokProduk::with('produk')
            ->whereHas('produk', function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('harga_beli')
                        ->orWhere('harga_beli', '=', 0);
                })
                    ->where(function ($q) {
                        $q->whereNull('harga_beli_rata_rata')
                            ->orWhere('harga_beli_rata_rata', '=', 0);
                    });
            })
            ->where('jumlah', '>', 0)
            ->get();

        return view('keuangan.jurnal_penyesuaian_persediaan.index', compact(
            'breadcrumbs',
            'akunPersediaan',
            'nilaiPersediaanAkuntansi',
            'nilaiPersediaanFisik',
            'selisih',
            'detailProduk',
            'gudangs',
            'produkTanpaHarga'
        ));
    }

    /**
     * Hitung nilai persediaan fisik berdasarkan stok di gudang
     * Menggunakan harga_beli_rata_rata jika ada, fallback ke harga_beli atau harga_pokok
     */
    private function calculatePhysicalInventoryValue()
    {
        $totalNilai = 0;

        $stokProduk = StokProduk::with('produk')->get();

        foreach ($stokProduk as $stok) {
            if ($stok->produk && $stok->jumlah > 0) {
                $hargaPokok = $stok->produk->harga_beli_rata_rata
                    ?? $stok->produk->harga_beli
                    ?? $stok->produk->harga_pokok
                    ?? 0;

                $nilaiStok = $stok->jumlah * $hargaPokok;
                $totalNilai += $nilaiStok;
            }
        }

        return $totalNilai;
    }

    /**
     * Ambil detail persediaan per produk
     */
    private function getInventoryDetails($gudangId = null)
    {
        $query = StokProduk::with(['produk', 'gudang']);

        if ($gudangId) {
            $query->where('gudang_id', $gudangId);
        }

        $stokProduk = $query->get();

        $details = [];
        foreach ($stokProduk as $stok) {
            if ($stok->produk && $stok->jumlah > 0) {
                $hargaPokok = $stok->produk->harga_beli_rata_rata
                    ?? $stok->produk->harga_beli
                    ?? $stok->produk->harga_pokok
                    ?? 0;

                $nilaiStok = $stok->jumlah * $hargaPokok;

                $details[] = [
                    'produk_id' => $stok->produk_id,
                    'nama_produk' => $stok->produk->nama,
                    'kode_produk' => $stok->produk->kode,
                    'gudang' => $stok->gudang->nama ?? '-',
                    'qty' => $stok->jumlah,
                    'satuan' => $stok->produk->satuan->nama ?? 'Pcs',
                    'harga_pokok' => $hargaPokok,
                    'nilai_total' => $nilaiStok,
                    'sumber_harga' => $stok->produk->harga_beli_rata_rata ? 'Harga Beli Rata-rata' : ($stok->produk->harga_beli ? 'Harga Beli' : 'Harga Pokok')
                ];
            }
        }

        return collect($details)->sortByDesc('nilai_total')->values()->all();
    }

    /**
     * Proses sinkronisasi/kalibrasi nilai persediaan
     * Membuat jurnal penyesuaian persediaan
     */
    public function sync(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Ambil akun persediaan (dari konfigurasi penyesuaian_stok)
            $persediaanConfig = AccountingConfiguration::where('transaction_type', 'penyesuaian_stok')
                ->where('account_key', 'persediaan')
                ->first();

            if (!$persediaanConfig || !$persediaanConfig->akun_id) {
                return back()->withErrors(['error' => 'Akun persediaan belum dikonfigurasi. Silakan konfigurasi di menu Kalibrasi Akun.']);
            }

            $akunPersediaan = AkunAkuntansi::find($persediaanConfig->akun_id);

            // Ambil akun HPP (untuk pengurangan persediaan)
            // Dalam sistem perpetual, selisih persediaan masuk ke HPP
            $akunHPP = AccountingConfiguration::where('transaction_type', 'hpp')
                ->where('account_key', 'harga_pokok_penjualan')
                ->first();

            if (!$akunHPP || !$akunHPP->akun_id) {
                return back()->withErrors(['error' => 'Akun HPP belum dikonfigurasi.']);
            }

            // Ambil akun modal pemilik (untuk penambahan persediaan)
            // Penambahan persediaan = koreksi saldo awal / modal barang yang belum tercatat
            $akunModal = AccountingConfiguration::where('transaction_type', 'saldo_awal')
                ->where('account_key', 'modal_pemilik')
                ->first();

            if (!$akunModal || !$akunModal->akun_id) {
                return back()->withErrors(['error' => 'Akun modal pemilik belum dikonfigurasi untuk penyesuaian saldo awal persediaan.']);
            }

            // Hitung nilai persediaan saat ini
            $nilaiPersediaanAkuntansi = JurnalUmum::where('akun_id', $akunPersediaan->id)
                ->where('is_posted', true)
                ->sum(DB::raw('debit - kredit'));

            // Hitung nilai persediaan fisik
            $nilaiPersediaanFisik = $this->calculatePhysicalInventoryValue();

            // Selisih
            $selisih = $nilaiPersediaanFisik - $nilaiPersediaanAkuntansi;

            // Log untuk debugging
            Log::info('Kalkulasi Penyesuaian Persediaan', [
                'nilai_akuntansi' => $nilaiPersediaanAkuntansi,
                'nilai_fisik' => $nilaiPersediaanFisik,
                'selisih' => $selisih,
                'selisih_positif' => $selisih > 0 ? 'Ya (Fisik > Akuntansi)' : 'Tidak (Fisik < Akuntansi)',
                'akun_persediaan_id' => $akunPersediaan->id,
                'jurnal_count' => JurnalUmum::where('akun_id', $akunPersediaan->id)->where('is_posted', true)->count()
            ]);

            // Jika tidak ada selisih, tidak perlu dibuat jurnal
            if (abs($selisih) < 0.01) {
                DB::rollBack();
                return back()->with('info', 'Tidak ada selisih antara nilai persediaan akuntansi dan fisik. Tidak perlu dilakukan penyesuaian.');
            }

            // Generate nomor referensi
            $lastJurnal = JurnalUmum::where('jenis_jurnal', 'penyesuaian_persediaan')
                ->orderBy('created_at', 'desc')
                ->first();

            $lastNumber = 1;
            if ($lastJurnal && preg_match('/JP-PERS-(\d+)/', $lastJurnal->no_referensi, $matches)) {
                $lastNumber = intval($matches[1]) + 1;
            }

            $noReferensi = 'JP-PERS-' . str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

            // Buat jurnal penyesuaian
            $entries = [];

            if ($selisih > 0) {
                // Nilai fisik lebih besar → tambah persediaan
                // Ini terjadi karena ada produk yang langsung diinput tanpa pencatatan jurnal
                // Atau ada koreksi saldo awal persediaan yang belum tercatat
                // Koreksi ini masuk ke Modal Pemilik, bukan pendapatan

                // Debit: Persediaan (menambah aset)
                $entries[] = [
                    'akun_id' => $akunPersediaan->id,
                    'debit' => $selisih,
                    'kredit' => 0,
                    'keterangan' => $request->keterangan . ' (Koreksi saldo awal/modal barang)'
                ];

                // Kredit: Modal Pemilik (koreksi modal/saldo awal)
                $entries[] = [
                    'akun_id' => $akunModal->akun_id,
                    'debit' => 0,
                    'kredit' => $selisih,
                    'keterangan' => $request->keterangan . ' (Koreksi saldo awal/modal barang)'
                ];
            } else {
                // Nilai fisik lebih kecil → kurangi persediaan
                // Ini berarti ada kehilangan/kerusakan/selisih yang harus diakui sebagai HPP
                // Dalam sistem perpetual, selisih persediaan = bagian dari HPP

                // Debit: HPP (menambah cost of goods sold)
                $entries[] = [
                    'akun_id' => $akunHPP->akun_id,
                    'debit' => abs($selisih),
                    'kredit' => 0,
                    'keterangan' => $request->keterangan
                ];

                // Kredit: Persediaan (mengurangi aset)
                $entries[] = [
                    'akun_id' => $akunPersediaan->id,
                    'debit' => 0,
                    'kredit' => abs($selisih),
                    'keterangan' => $request->keterangan
                ];
            }            // Simpan jurnal
            foreach ($entries as $entry) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'],
                    'kredit' => $entry['kredit'],
                    'keterangan' => $entry['keterangan'],
                    'jenis_jurnal' => 'penyesuaian',
                    'is_posted' => true, // Langsung diposting
                    'user_id' => Auth::id(),
                ]);
            }

            // Log aktivitas
            Log::info('Jurnal Penyesuaian Persediaan Created', [
                'no_referensi' => $noReferensi,
                'tanggal' => $request->tanggal,
                'nilai_akuntansi' => $nilaiPersediaanAkuntansi,
                'nilai_fisik' => $nilaiPersediaanFisik,
                'selisih' => $selisih,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('keuangan.jurnal-penyesuaian-persediaan.index')
                ->with('success', 'Kalibrasi persediaan berhasil! Jurnal penyesuaian nomor ' . $noReferensi . ' telah dibuat dengan selisih Rp ' . number_format(abs($selisih), 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error syncing inventory: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Lihat history jurnal penyesuaian persediaan
     */
    public function history()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Keuangan', 'url' => '#'],
            ['label' => 'Kalibrasi Persediaan', 'url' => route('keuangan.jurnal-penyesuaian-persediaan.index')],
            ['label' => 'History', 'url' => null],
        ];

        $jurnals = JurnalUmum::where('jenis_jurnal', 'penyesuaian_persediaan')
            ->with(['akun', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('no_referensi', 'desc')
            ->get()
            ->groupBy('no_referensi');

        return view('keuangan.jurnal_penyesuaian_persediaan.history', compact('breadcrumbs', 'jurnals'));
    }

    /**
     * Export data untuk review
     */
    public function export(Request $request)
    {
        $gudangId = $request->get('gudang_id');
        $details = $this->getInventoryDetails($gudangId);

        // Bisa ditambahkan export ke Excel/PDF
        // Untuk sementara return JSON
        return response()->json([
            'success' => true,
            'data' => $details,
            'total' => array_sum(array_column($details, 'nilai_total'))
        ]);
    }
}
