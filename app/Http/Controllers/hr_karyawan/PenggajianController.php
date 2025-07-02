<?php

namespace App\Http\Controllers\hr_karyawan;

use App\Http\Controllers\Controller;
use App\Traits\HasPDFQRCode;
use App\Models\Karyawan;
use App\Models\KomponenGaji;
use App\Models\Penggajian;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Produk;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggajianController extends Controller
{
    use HasPDFQRCode;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get selected month and year from request
        $selectedMonth = $request->input('bulan', $currentMonth);
        $selectedYear = $request->input('tahun', $currentYear);

        // Ensure selectedMonth and selectedYear are integers
        $selectedMonth = is_numeric($selectedMonth) ? (int)$selectedMonth : $currentMonth;
        $selectedYear = is_numeric($selectedYear) ? (int)$selectedYear : $currentYear;

        // Base query for existing payment records
        $query = Penggajian::with(['karyawan', 'approver', 'komponenGaji'])
            ->where('bulan', $selectedMonth)
            ->where('tahun', $selectedYear);

        // Get all active employees
        $activeEmployees = Karyawan::where('status', 'aktif')->get();

        // Pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('karyawan', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && !empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->has('tahun') && !empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Pengurutan
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Validasi field yang diizinkan untuk sort
        $allowedSortFields = ['bulan', 'tahun', 'total_gaji', 'status', 'tanggal_bayar', 'created_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get existing payment records for the selected month/year
        $existingPayments = Penggajian::where('bulan', $selectedMonth)
            ->where('tahun', $selectedYear)
            ->get();

        // Extract employee IDs who already have payment records
        $paidEmployeeIds = $existingPayments->pluck('karyawan_id')->toArray();

        // Pagination
        $perPage = $request->input('per_page', 10);
        $penggajian = $query->paginate($perPage);

        // Get unpaid employees for the current month/year
        $unpaidEmployees = $activeEmployees->whereNotIn('id', $paidEmployeeIds);

        // Handle AJAX request - only return JSON for explicit AJAX requests with our custom parameter
        $isExplicitAjaxRequest = $request->has('ajax_request') && $request->get('ajax_request') === '1';

        if ($isExplicitAjaxRequest) {
            try {
                // Debug information
                Log::info('AJAX Penggajian Request', [
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                    'penggajian_count' => $penggajian->count(),
                    'unpaidEmployees_count' => $unpaidEmployees->count(),
                ]);

                $view = view('hr_karyawan.penggajian_dan_tunjangan._table_body', compact(
                    'penggajian',
                    'unpaidEmployees',
                    'selectedMonth',
                    'selectedYear',
                    'currentMonth',
                    'currentYear'
                ))->render();

                $pagination = view('vendor.pagination.tailwind-custom', ['paginator' => $penggajian])->render();

                return response()->json([
                    'success' => true,
                    'html' => $view,
                    'pagination' => $pagination,
                    'total' => $penggajian->total(),
                    'first_item' => $penggajian->firstItem(),
                    'last_item' => $penggajian->lastItem(),
                    'currentMonth' => $currentMonth,
                    'currentYear' => $currentYear,
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                ]);
            } catch (\Exception $e) {
                Log::error('Error in Penggajian AJAX: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                ]);

                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }
        }

        // Response untuk request normal
        return view('hr_karyawan.penggajian_dan_tunjangan.index', [
            'penggajian' => $penggajian,
            'unpaidEmployees' => $unpaidEmployees,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'currentPage' => 'Penggajian Karyawan',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Penggajian Karyawan']
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $karyawan_id = $request->query('karyawan_id');
        $karyawan = Karyawan::where('status', 'aktif')->get();
        $selectedKaryawan = null;
        $gajiPokok = 0;

        if ($karyawan_id) {
            $selectedKaryawan = Karyawan::find($karyawan_id);
            if ($selectedKaryawan) {
                $gajiPokok = $selectedKaryawan->gaji_pokok;
            }
        }

        // Get selected month and year from request or use current date
        $bulanSekarang = intval($request->query('bulan', Carbon::now()->month));
        $tahunSekarang = intval($request->query('tahun', Carbon::now()->year));

        // Log the parameters to debug
        Log::info('Creating payroll form with params', [
            'karyawan_id' => $karyawan_id,
            'bulan' => $bulanSekarang,
            'tahun' => $tahunSekarang,
            'request_bulan' => $request->query('bulan'),
            'request_tahun' => $request->query('tahun'),
        ]);

        return view('hr_karyawan.penggajian_dan_tunjangan.create', [
            'karyawan' => $karyawan,
            'selectedKaryawan' => $selectedKaryawan,
            'gajiPokok' => $gajiPokok,
            'bulanSekarang' => $bulanSekarang,
            'tahunSekarang' => $tahunSekarang,
            'currentPage' => 'Tambah Penggajian',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Penggajian Karyawan', 'url' => route('hr.penggajian.index')],
                ['name' => 'Tambah Penggajian']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
            'status' => 'required|in:draft,disetujui,dibayar',
            'catatan' => 'nullable|string',
            'komponenGaji' => 'nullable|array',
            'komponenGaji.*.nama_komponen' => 'required|string',
            'komponenGaji.*.jenis' => 'required|in:pendapatan,potongan',
            'komponenGaji.*.nilai' => 'required|numeric',
            'komponenGaji.*.keterangan' => 'nullable|string',
        ]);

        // Cek apakah sudah ada data penggajian untuk karyawan, bulan dan tahun yang sama
        $exists = Penggajian::where('karyawan_id', $request->karyawan_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data penggajian untuk karyawan, bulan dan tahun ini sudah ada')->withInput();
        }

        // Hitung komisi dari sales order jika ada
        $komisiData = $this->hitungKomisiKaryawan($request->karyawan_id, $request->bulan, $request->tahun);
        $komisi = $komisiData['komisi'];
        $salesOrderIds = $komisiData['salesOrderIds'];

        // Hitung total gaji
        $totalGaji = $request->gaji_pokok +
            ($request->tunjangan ?? 0) +
            ($request->bonus ?? 0) +
            ($request->lembur ?? 0) +
            $komisi -
            ($request->potongan ?? 0);

        // Tambahkan komponen gaji lainnya jika ada
        if ($request->has('komponenGaji')) {
            foreach ($request->komponenGaji as $komponen) {
                if ($komponen['jenis'] == 'pendapatan') {
                    $totalGaji += $komponen['nilai'];
                } else {
                    $totalGaji -= $komponen['nilai'];
                }
            }
        }

        DB::beginTransaction();
        try {
            // Simpan data penggajian
            $penggajian = Penggajian::create([
                'karyawan_id' => $request->karyawan_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'gaji_pokok' => $request->gaji_pokok,
                'tunjangan' => $request->tunjangan,
                'bonus' => $request->bonus,
                'lembur' => $request->lembur,
                'potongan' => $request->potongan,
                'total_gaji' => $totalGaji,
                'tanggal_bayar' => $request->tanggal_bayar,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'disetujui_oleh' => $request->status == 'disetujui' ? Auth::id() : null,
            ]);

            // Tambahkan komponen komisi jika ada
            if ($komisi > 0) {
                $keteranganKomisi = 'Komisi dari sales order bulan ' . $request->bulan . ' tahun ' . $request->tahun;

                // Tambahkan sales order nomors jika ada
                if (!empty($salesOrderIds)) {
                    $salesOrderNomors = $this->getSalesOrderNomors($salesOrderIds);
                    $keteranganKomisi .= ' (SO: ' . implode(', ', $salesOrderNomors) . ')';
                }

                KomponenGaji::create([
                    'penggajian_id' => $penggajian->id,
                    'nama_komponen' => 'Komisi Penjualan',
                    'jenis' => 'pendapatan',
                    'nilai' => $komisi,
                    'keterangan' => $keteranganKomisi
                ]);
            }

            // Simpan komponen gaji lainnya jika ada
            if ($request->has('komponenGaji')) {
                foreach ($request->komponenGaji as $komponen) {
                    KomponenGaji::create([
                        'penggajian_id' => $penggajian->id,
                        'nama_komponen' => $komponen['nama_komponen'],
                        'jenis' => $komponen['jenis'],
                        'nilai' => $komponen['nilai'],
                        'keterangan' => $komponen['keterangan'] ?? null
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('hr.penggajian.index')->with('success', 'Data penggajian berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penggajian = Penggajian::with(['karyawan', 'approver', 'komponenGaji'])->findOrFail($id);

        // Load cash accounts and bank accounts for payment form
        $cashAccounts = \App\Models\Kas::where('is_aktif', true)->get();
        $bankAccounts = \App\Models\RekeningBank::where('is_aktif', true)->get();

        return view('hr_karyawan.penggajian_dan_tunjangan.show', [
            'penggajian' => $penggajian,
            'cashAccounts' => $cashAccounts,
            'bankAccounts' => $bankAccounts,
            'currentPage' => 'Detail Penggajian',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Penggajian Karyawan', 'url' => route('hr.penggajian.index')],
                ['name' => 'Detail Penggajian']
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penggajian = Penggajian::with(['karyawan', 'komponenGaji'])->findOrFail($id);
        $karyawan = Karyawan::where('status', 'aktif')->get();

        return view('hr_karyawan.penggajian_dan_tunjangan.edit', [
            'penggajian' => $penggajian,
            'karyawan' => $karyawan,
            'currentPage' => 'Edit Penggajian',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Penggajian Karyawan', 'url' => route('hr.penggajian.index')],
                ['name' => 'Edit Penggajian']
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
            'status' => 'required|in:draft,disetujui,dibayar',
            'catatan' => 'nullable|string',
            'komponenGaji' => 'nullable|array',
            'komponenGaji.*.id' => 'nullable|exists:komponen_gaji,id',
            'komponenGaji.*.nama_komponen' => 'required|string',
            'komponenGaji.*.jenis' => 'required|in:pendapatan,potongan',
            'komponenGaji.*.nilai' => 'required|numeric',
            'komponenGaji.*.keterangan' => 'nullable|string',
        ]);

        $penggajian = Penggajian::findOrFail($id);

        // Hitung total gaji
        $totalGaji = $request->gaji_pokok +
            ($request->tunjangan ?? 0) +
            ($request->bonus ?? 0) +
            ($request->lembur ?? 0) -
            ($request->potongan ?? 0);

        // Tambahkan komponen gaji
        if ($request->has('komponenGaji')) {
            foreach ($request->komponenGaji as $komponen) {
                if ($komponen['jenis'] == 'pendapatan') {
                    $totalGaji += $komponen['nilai'];
                } else {
                    $totalGaji -= $komponen['nilai'];
                }
            }
        }

        DB::beginTransaction();
        try {
            // Update data penggajian
            $penggajian->update([
                'gaji_pokok' => $request->gaji_pokok,
                'tunjangan' => $request->tunjangan,
                'bonus' => $request->bonus,
                'lembur' => $request->lembur,
                'potongan' => $request->potongan,
                'total_gaji' => $totalGaji,
                'tanggal_bayar' => $request->tanggal_bayar,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'disetujui_oleh' => $request->status == 'disetujui' && !$penggajian->disetujui_oleh ? Auth::id() : $penggajian->disetujui_oleh,
            ]);

            // Update atau tambah komponen gaji
            if ($request->has('komponenGaji')) {
                $existingIds = [];

                foreach ($request->komponenGaji as $komponen) {
                    if (isset($komponen['id'])) {
                        // Update komponen yang sudah ada
                        $komponenGaji = KomponenGaji::findOrFail($komponen['id']);
                        $komponenGaji->update([
                            'nama_komponen' => $komponen['nama_komponen'],
                            'jenis' => $komponen['jenis'],
                            'nilai' => $komponen['nilai'],
                            'keterangan' => $komponen['keterangan'] ?? null
                        ]);
                        $existingIds[] = $komponen['id'];
                    } else {
                        // Tambah komponen baru
                        $komponenGaji = KomponenGaji::create([
                            'penggajian_id' => $penggajian->id,
                            'nama_komponen' => $komponen['nama_komponen'],
                            'jenis' => $komponen['jenis'],
                            'nilai' => $komponen['nilai'],
                            'keterangan' => $komponen['keterangan'] ?? null
                        ]);
                        $existingIds[] = $komponenGaji->id;
                    }
                }

                // Hapus komponen yang tidak ada di request
                KomponenGaji::where('penggajian_id', $penggajian->id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            } else {
                // Hapus semua komponen jika tidak ada di request
                KomponenGaji::where('penggajian_id', $penggajian->id)->delete();
            }

            DB::commit();
            return redirect()->route('hr.penggajian.index')->with('success', 'Data penggajian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $penggajian = Penggajian::findOrFail($id);

            // Hapus komponen gaji terkait
            KomponenGaji::where('penggajian_id', $id)->delete();

            // Hapus data penggajian
            $penggajian->delete();

            return redirect()->route('hr.penggajian.index')->with('success', 'Data penggajian berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hitung komisi karyawan berdasarkan sales order
     * @return array Returns an array with total commission and processed sales order IDs
     */
    private function hitungKomisiKaryawan($karyawanId, $bulan, $tahun)
    {
        // Get the user_id associated with karyawan
        $karyawan = Karyawan::findOrFail($karyawanId);
        if (!$karyawan->user_id) {
            return ['komisi' => 0, 'salesOrderIds' => []]; // Karyawan tidak terkait dengan user, tidak bisa menghitung komisi
        }

        $userId = $karyawan->user_id;

        // Ambil semua sales order oleh karyawan ini yang sudah LUNAS dan dibayar pada bulan/tahun tertentu
        // Komisi dihitung berdasarkan kapan sales order menjadi lunas, bukan kapan SO dibuat
        $salesOrders = SalesOrder::where('user_id', $userId)
            ->where('status_pembayaran', 'lunas') // Hanya sales order yang sudah lunas
            ->whereHas('invoices.pembayaranPiutang', function ($query) use ($bulan, $tahun) {
                // Filter berdasarkan tanggal pembayaran yang membuat SO menjadi lunas
                $query->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            })
            ->get();

        $totalKomisi = 0;
        $processedSalesOrderIds = [];

        // Cek apakah sudah ada komisi yang dihitung pada periode sebelumnya
        // Karena sekarang komisi dihitung berdasarkan tanggal pembayaran lunas,
        // kita perlu mengecek periode berdasarkan bulan/tahun komisi yang sudah dihitung
        $existingKomisi = Penggajian::where('karyawan_id', $karyawanId)
            ->where(function ($query) use ($bulan, $tahun) {
                // Cek periode sebelumnya atau sama (bulan dan tahun sama atau sebelumnya)
                $query->where(function ($q) use ($bulan, $tahun) {
                    $q->where('tahun', '<', $tahun)
                        ->orWhere(function ($q2) use ($bulan, $tahun) {
                            $q2->where('tahun', $tahun)
                                ->where('bulan', '<=', $bulan);
                        });
                });
            })
            ->whereHas('komponenGaji', function ($query) {
                $query->where('nama_komponen', 'like', '%Komisi Penjualan%');
            })
            ->pluck('id')
            ->toArray();

        // Dapatkan semua sales order nomor yang sudah pernah dihitung komisinya
        $alreadyProcessedSalesOrderIds = [];
        if (!empty($existingKomisi)) {
            $alreadyProcessedSalesOrderIds = KomponenGaji::whereIn('penggajian_id', $existingKomisi)
                ->where('nama_komponen', 'like', '%Komisi Penjualan%')
                ->where(function ($query) {
                    $query->where('keterangan', 'like', '%SO:%')
                        ->orWhere('keterangan', 'like', '%sales order ID:%');
                })
                ->get()
                ->map(function ($item) {
                    // Extract sales order info from keterangan and convert to IDs

                    // Check for new format with sales order nomors
                    if (preg_match('/SO: ([\w\-,\s]+)/', $item->keterangan, $matches)) {
                        $salesOrderNomors = array_map('trim', explode(',', $matches[1]));
                        // Convert nomor back to IDs
                        return SalesOrder::whereIn('nomor', $salesOrderNomors)->pluck('id')->toArray();
                    }

                    // Check for old format with sales order IDs (backward compatibility)
                    if (preg_match('/sales order ID: ([\d,]+)/', $item->keterangan, $matches)) {
                        $salesOrderIds = array_map('trim', explode(',', $matches[1]));
                        return array_map('intval', $salesOrderIds);
                    }

                    return null;
                })
                ->flatten()
                ->filter()
                ->toArray();
        }

        foreach ($salesOrders as $order) {
            // Skip if this sales order has already been processed in previous months
            if (in_array($order->id, $alreadyProcessedSalesOrderIds)) {
                continue;
            }

            $details = SalesOrderDetail::where('sales_order_id', $order->id)->get();
            $orderKomisi = 0;

            foreach ($details as $detail) {
                // Ambil data produk
                $produk = Produk::find($detail->produk_id);

                if ($produk) {
                    // Hitung Netto Penjualan dan Netto Beli
                    $nettoPenjualan = $detail->harga * $detail->quantity; // Harga jual × quantity
                    $nettoBeli = $produk->harga_beli * $detail->quantity; // Harga beli × quantity

                    if ($nettoBeli > 0) {
                        // 1. Hitung Margin % = (Netto Penjualan - Netto Beli) / Netto Beli × 100
                        $marginPersen = (($nettoPenjualan - $nettoBeli) / $nettoBeli) * 100;

                        // 2. Cari Tier Persentase Komisi dari tabel
                        $komisiRate = $this->getKomisiRateByMargin($marginPersen);

                        // 3. Hitung Komisi: Netto Penjualan × %Komisi
                        $komisi = $nettoPenjualan * ($komisiRate / 100);

                        $orderKomisi += $komisi;
                    }
                }
            }

            // Jika ada komisi untuk order ini, tambahkan ke total dan track sales order ID
            if ($orderKomisi > 0) {
                $totalKomisi += $orderKomisi;
                $processedSalesOrderIds[] = $order->id;
            }
        }

        return [
            'komisi' => $totalKomisi,
            'salesOrderIds' => $processedSalesOrderIds
        ];
    }

    /**
     * Get commission rate based on margin percentage using the tiered system
     * @param float $marginPersen
     * @return float Commission rate percentage
     */
    private function getKomisiRateByMargin($marginPersen)
    {
        // Tabel tier komisi berdasarkan margin
        $komisiTiers = [
            ['min' => 18.00, 'max' => 20.00, 'rate' => 1.00],
            ['min' => 20.50, 'max' => 25.00, 'rate' => 1.25],
            ['min' => 25.50, 'max' => 30.00, 'rate' => 1.50],
            ['min' => 30.50, 'max' => 35.00, 'rate' => 1.75],
            ['min' => 35.50, 'max' => 40.00, 'rate' => 2.00],
            ['min' => 40.50, 'max' => 45.00, 'rate' => 2.25],
            ['min' => 45.50, 'max' => 50.00, 'rate' => 2.50],
            ['min' => 50.50, 'max' => 55.00, 'rate' => 2.75],
            ['min' => 55.50, 'max' => 60.00, 'rate' => 3.00],
            ['min' => 60.50, 'max' => 65.00, 'rate' => 3.25],
            ['min' => 65.50, 'max' => 70.00, 'rate' => 3.50],
            ['min' => 70.50, 'max' => 75.00, 'rate' => 3.75],
            ['min' => 75.50, 'max' => 80.00, 'rate' => 4.00],
            ['min' => 80.50, 'max' => 85.00, 'rate' => 4.25],
            ['min' => 85.50, 'max' => 90.00, 'rate' => 4.50],
            ['min' => 90.50, 'max' => 95.00, 'rate' => 4.75],
            ['min' => 95.50, 'max' => 100.00, 'rate' => 5.00],
            ['min' => 101.00, 'max' => 110.00, 'rate' => 5.25],
            ['min' => 111.00, 'max' => 125.00, 'rate' => 5.50],
            ['min' => 126.00, 'max' => 140.00, 'rate' => 5.75],
            ['min' => 141.00, 'max' => 160.00, 'rate' => 6.00],
            ['min' => 161.00, 'max' => 180.00, 'rate' => 6.25],
            ['min' => 181.00, 'max' => 200.00, 'rate' => 6.50],
            ['min' => 201.00, 'max' => 225.00, 'rate' => 7.00],
            ['min' => 226.00, 'max' => 250.00, 'rate' => 7.25],
            ['min' => 251.00, 'max' => 275.00, 'rate' => 7.50],
            ['min' => 276.00, 'max' => 300.00, 'rate' => 8.00],
            ['min' => 301.00, 'max' => 325.00, 'rate' => 8.25],
            ['min' => 326.00, 'max' => 350.00, 'rate' => 8.50],
            ['min' => 351.00, 'max' => 400.00, 'rate' => 9.00],
            ['min' => 401.00, 'max' => 450.00, 'rate' => 9.50],
            ['min' => 451.00, 'max' => 500.00, 'rate' => 10.00],
            ['min' => 501.00, 'max' => 600.00, 'rate' => 10.50],
            ['min' => 601.00, 'max' => 700.00, 'rate' => 11.00],
            ['min' => 701.00, 'max' => 800.00, 'rate' => 11.50],
            ['min' => 801.00, 'max' => 900.00, 'rate' => 12.00],
            ['min' => 901.00, 'max' => 1000.00, 'rate' => 12.50],
            ['min' => 1001.00, 'max' => 1100.00, 'rate' => 13.00],
            ['min' => 1101.00, 'max' => 1200.00, 'rate' => 13.50],
            ['min' => 1201.00, 'max' => 1300.00, 'rate' => 14.00],
            ['min' => 1301.00, 'max' => 1400.00, 'rate' => 14.50],
            ['min' => 1401.00, 'max' => 1500.00, 'rate' => 15.00],
            ['min' => 1501.00, 'max' => 1600.00, 'rate' => 15.50],
            ['min' => 1601.00, 'max' => 1700.00, 'rate' => 16.00],
            ['min' => 1701.00, 'max' => 1800.00, 'rate' => 16.50],
            ['min' => 1801.00, 'max' => 1900.00, 'rate' => 17.00],
            ['min' => 1901.00, 'max' => 2000.00, 'rate' => 17.50],
            ['min' => 2001.00, 'max' => 2100.00, 'rate' => 18.00],
            ['min' => 2101.00, 'max' => 2200.00, 'rate' => 18.50],
            ['min' => 2201.00, 'max' => 2300.00, 'rate' => 19.00],
            ['min' => 2301.00, 'max' => 2400.00, 'rate' => 19.50],
            ['min' => 2401.00, 'max' => 2501.00, 'rate' => 20.00],
            ['min' => 2501.00, 'max' => 2600.00, 'rate' => 20.50],
            ['min' => 2601.00, 'max' => 2700.00, 'rate' => 21.00],
            ['min' => 2701.00, 'max' => 2800.00, 'rate' => 21.50],
            ['min' => 2801.00, 'max' => 2900.00, 'rate' => 22.00],
            ['min' => 2901.00, 'max' => 3000.00, 'rate' => 22.50],
            ['min' => 3001.00, 'max' => 3100.00, 'rate' => 23.00],
            ['min' => 3101.00, 'max' => 3200.00, 'rate' => 23.50],
            ['min' => 3201.00, 'max' => 3300.00, 'rate' => 24.00],
            ['min' => 3301.00, 'max' => 3400.00, 'rate' => 24.50],
            ['min' => 3401.00, 'max' => 3500.00, 'rate' => 25.00],
            ['min' => 3501.00, 'max' => 3600.00, 'rate' => 25.50],
            ['min' => 3601.00, 'max' => 3700.00, 'rate' => 26.00],
            ['min' => 3701.00, 'max' => 3800.00, 'rate' => 26.50],
            ['min' => 3801.00, 'max' => 3900.00, 'rate' => 27.00],
            ['min' => 3901.00, 'max' => 4000.00, 'rate' => 27.50],
            ['min' => 4001.00, 'max' => 4100.00, 'rate' => 28.00],
            ['min' => 4101.00, 'max' => 4200.00, 'rate' => 28.50],
            ['min' => 4201.00, 'max' => 4300.00, 'rate' => 29.00],
            ['min' => 4301.00, 'max' => 4400.00, 'rate' => 29.50],
            ['min' => 4401.00, 'max' => 4500.00, 'rate' => 30.00],
        ];

        // Cari tier yang sesuai dengan margin
        foreach ($komisiTiers as $tier) {
            if ($marginPersen >= $tier['min'] && $marginPersen <= $tier['max']) {
                return $tier['rate'];
            }
        }

        // Jika margin lebih dari 4500%, gunakan rate tertinggi
        if ($marginPersen > 4500.00) {
            return 30.00;
        }

        // Jika margin kurang dari 18%, tidak ada komisi
        return 0;
    }

    /**
     * Get sales order nomors by IDs
     */
    private function getSalesOrderNomors($salesOrderIds)
    {
        if (empty($salesOrderIds)) {
            return [];
        }

        return SalesOrder::whereIn('id', $salesOrderIds)->pluck('nomor')->toArray();
    }

    /**
     * Ambil data komisi karyawan (untuk API)
     */
    public function getKomisiKaryawan(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        // Log request parameters
        Log::info('Calculating commission', [
            'karyawan_id' => $request->karyawan_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        $komisiData = $this->hitungKomisiKaryawan($request->karyawan_id, $request->bulan, $request->tahun);
        $komisi = $komisiData['komisi'];
        $salesOrderIds = $komisiData['salesOrderIds'];

        // Log sales order IDs found
        Log::info('Sales orders found for commission', [
            'count' => count($salesOrderIds),
            'ids' => $salesOrderIds
        ]);

        // Get detailed sales order data
        $salesOrderDetails = [];
        if (!empty($salesOrderIds)) {
            Log::info('Getting detailed sales order data for IDs', ['ids' => $salesOrderIds]);

            $salesOrders = SalesOrder::with(['customer', 'details.produk'])
                ->whereIn('id', $salesOrderIds)
                ->get();

            Log::info('Found sales orders', ['count' => $salesOrders->count()]);

            foreach ($salesOrders as $order) {
                $totalHargaJual = 0;
                $totalHargaBeli = 0;
                $totalMargin = 0;
                $totalKomisi = 0;
                $productNames = [];

                Log::debug('Processing sales order', [
                    'id' => $order->id,
                    'nomor' => $order->nomor,
                    'detail_count' => $order->details->count()
                ]);

                foreach ($order->details as $detail) {
                    $produk = $detail->produk;
                    if ($produk) {
                        // Hitung Netto Penjualan dan Netto Beli
                        $nettoPenjualan = $detail->harga * $detail->quantity; // Netto Penjualan
                        $nettoBeli = $produk->harga_beli * $detail->quantity; // Netto Beli
                        $margin = $nettoPenjualan - $nettoBeli;

                        if ($nettoBeli > 0) {
                            // 1. Hitung Margin % = (Netto Penjualan - Netto Beli) / Netto Beli × 100
                            $marginPersen = ($margin / $nettoBeli) * 100;

                            // 2. Cari Tier Persentase Komisi dari tabel
                            $komisiRate = $this->getKomisiRateByMargin($marginPersen);

                            // 3. Hitung Komisi: Netto Penjualan × %Komisi
                            $detailKomisi = $nettoPenjualan * ($komisiRate / 100);
                            $totalKomisi += $detailKomisi;
                        }

                        $totalHargaJual += $nettoPenjualan;
                        $totalHargaBeli += $nettoBeli;
                        $totalMargin += $margin;

                        // Add product name
                        $productNames[] = $produk->nama;
                    }
                }

                // Calculate margin percentage
                $marginPersen = ($totalHargaBeli > 0) ? ($totalMargin / $totalHargaBeli) * 100 : 0;

                $salesOrderDetails[] = [
                    'id' => $order->id,
                    'nomor' => $order->nomor,
                    'tanggal' => $order->tanggal,
                    'customer' => $order->customer ? $order->customer->nama : 'N/A',
                    'produk' => implode(', ', array_slice($productNames, 0, 3)) . (count($productNames) > 3 ? '...' : ''),
                    'harga_jual' => $totalHargaJual,
                    'harga_beli' => $totalHargaBeli,
                    'margin' => $totalMargin,
                    'margin_persen' => round($marginPersen, 2),
                    'komisi' => $totalKomisi
                ];
            }
        }

        // Log the sales order details before sending response
        Log::info('Komisi calculation response', [
            'karyawan_id' => $request->karyawan_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'komisi' => $komisi,
            'sales_count' => count($salesOrderIds),
            'has_sales_details' => !empty($salesOrderDetails),
            'sales_details_count' => count($salesOrderDetails),
            'first_record' => !empty($salesOrderDetails) ? array_keys($salesOrderDetails[0]) : 'no records'
        ]);

        return response()->json([
            'success' => true,
            'komisi' => $komisi,
            'formatted_komisi' => number_format($komisi, 0, ',', '.'),
            'sales_count' => count($salesOrderIds),
            'sales_details' => $salesOrderDetails,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Approve a payroll
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_note' => 'nullable|string',
        ]);

        try {
            $penggajian = Penggajian::findOrFail($id);

            // Check if payroll is already approved or paid
            if ($penggajian->status != 'draft') {
                return back()->with('error', 'Penggajian ini tidak dapat disetujui karena statusnya bukan draft.');
            }

            DB::beginTransaction();

            // Update status to approved
            $penggajian->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
                'catatan' => $penggajian->catatan . "\n\nCatatan Persetujuan: " . $request->approval_note,
            ]);

            DB::commit();
            return redirect()->route('hr.penggajian.show', $penggajian->id)->with('success', 'Penggajian berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process payment for an approved payroll
     */
    public function processPayment(Request $request, $id)
    {
        // Add debug logging
        Log::info('processPayment called', [
            'id' => $id,
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        // Enhanced validation rules for payment processing
        $validationRules = [
            'payment_method' => 'required|in:cash,bank',
            'kas_id' => 'required_if:payment_method,cash|nullable|exists:kas,id',
            'rekening_id' => 'required_if:payment_method,bank|nullable|exists:rekening_bank,id',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_note' => 'nullable|string|max:500',
        ];

        Log::info('Starting validation with rules', $validationRules);

        // Custom validation messages for better error feedback
        $messages = [
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran harus berupa kas atau bank.',
            'kas_id.required_if' => 'Akun kas harus dipilih untuk pembayaran tunai.',
            'kas_id.exists' => 'Akun kas yang dipilih tidak valid.',
            'rekening_id.required_if' => 'Rekening bank harus dipilih untuk transfer bank.',
            'rekening_id.exists' => 'Rekening bank yang dipilih tidak valid.',
            'payment_date.required' => 'Tanggal pembayaran harus diisi.',
            'payment_date.date' => 'Format tanggal pembayaran tidak valid.',
            'payment_date.before_or_equal' => 'Tanggal pembayaran tidak boleh lebih dari hari ini.',
            'payment_note.max' => 'Catatan pembayaran maksimal 500 karakter.',
        ];

        try {
            $validatedData = $request->validate($validationRules, $messages);
            Log::info('Validation passed', $validatedData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            $penggajian = Penggajian::with('karyawan')->findOrFail($id);
            Log::info('Penggajian found', ['id' => $penggajian->id, 'status' => $penggajian->status]);

            // Enhanced business logic validation
            if ($penggajian->status === 'dibayar') {
                Log::warning('Payroll already paid', ['id' => $penggajian->id, 'tanggal_bayar' => $penggajian->tanggal_bayar]);
                return back()->with('error', 'Penggajian ini sudah dibayar pada tanggal ' .
                    \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('d F Y') . '.');
            }

            if ($penggajian->status !== 'disetujui') {
                Log::warning('Invalid payroll status for payment', ['status' => $penggajian->status]);
                return back()->with('error', 'Penggajian ini tidak dapat dibayar karena statusnya bukan disetujui.');
            }

            // Validate payment amount
            if ($penggajian->total_gaji <= 0) {
                Log::warning('Invalid payment amount', ['total_gaji' => $penggajian->total_gaji]);
                return back()->with('error', 'Total gaji tidak valid untuk pembayaran.');
            }

            // Additional validation for account status and balance
            if ($request->payment_method === 'cash') {
                $kas = \App\Models\Kas::findOrFail($request->kas_id);
                if (!$kas->is_aktif) {
                    Log::warning('Inactive cash account selected', ['kas_id' => $kas->id]);
                    return back()->with('error', "Akun kas {$kas->nama} tidak aktif.");
                }
                if ($kas->saldo < $penggajian->total_gaji) {
                    Log::warning('Insufficient cash balance', [
                        'kas_saldo' => $kas->saldo,
                        'payment_amount' => $penggajian->total_gaji
                    ]);
                    return back()->with('error', "Saldo kas {$kas->nama} tidak mencukupi. Saldo: Rp " .
                        number_format($kas->saldo, 0, ',', '.') . ", Dibutuhkan: Rp " .
                        number_format($penggajian->total_gaji, 0, ',', '.'));
                }
            } elseif ($request->payment_method === 'bank') {
                $rekening = \App\Models\RekeningBank::findOrFail($request->rekening_id);
                if (!$rekening->is_aktif) {
                    Log::warning('Inactive bank account selected', ['rekening_id' => $rekening->id]);
                    return back()->with('error', "Rekening bank {$rekening->nama_bank} tidak aktif.");
                }
                if ($rekening->saldo < $penggajian->total_gaji) {
                    Log::warning('Insufficient bank balance', [
                        'rekening_saldo' => $rekening->saldo,
                        'payment_amount' => $penggajian->total_gaji
                    ]);
                    return back()->with('error', "Saldo rekening {$rekening->nama_bank} {$rekening->nomor_rekening} tidak mencukupi. Saldo: Rp " .
                        number_format($rekening->saldo, 0, ',', '.') . ", Dibutuhkan: Rp " .
                        number_format($penggajian->total_gaji, 0, ',', '.'));
                }
            }

            Log::info('Starting database transaction');
            DB::beginTransaction();

            // Get employee name for transaction description
            $employeeName = $penggajian->karyawan->nama_lengkap;
            $paymentDate = $request->payment_date;
            $paymentAmount = $penggajian->total_gaji;
            $transactionDescription = "Pembayaran gaji {$employeeName} periode " .
                $this->getNamaBulan($penggajian->bulan) . " {$penggajian->tahun}";

            if ($request->payment_note) {
                $transactionDescription .= " - " . $request->payment_note;
            }

            // Validate account exists and has sufficient balance
            // Balance checks are done for validation only - actual deduction will be handled by observer
            if ($request->payment_method == 'cash') {
                Log::info('Validating cash payment account', ['kas_id' => $request->kas_id]);
                $kas = \App\Models\Kas::findOrFail($request->kas_id);
                Log::info('Cash account validated', [
                    'kas_name' => $kas->nama,
                    'current_balance' => $kas->saldo,
                    'payment_amount' => $paymentAmount
                ]);
            } else { // bank transfer
                Log::info('Validating bank payment account', ['rekening_id' => $request->rekening_id]);
                $rekening = \App\Models\RekeningBank::findOrFail($request->rekening_id);
                Log::info('Bank account validated', [
                    'bank_name' => $rekening->nama_bank,
                    'account_number' => $rekening->nomor_rekening,
                    'current_balance' => $rekening->saldo,
                    'payment_amount' => $paymentAmount
                ]);
            }

            Log::info('Updating payroll status to paid', ['penggajian_id' => $penggajian->id]);
            // Update payroll status to paid and save payment method information
            $updateData = [
                'status' => 'dibayar',
                'tanggal_bayar' => $paymentDate,
                'metode_pembayaran' => $request->payment_method === 'cash' ? 'kas' : 'bank',
                'kas_id' => $request->payment_method === 'cash' ? $request->kas_id : null,
                'rekening_id' => $request->payment_method === 'bank' ? $request->rekening_id : null,
            ];

            if ($request->payment_note) {
                $updateData['catatan'] = ($penggajian->catatan ? $penggajian->catatan . "\n\n" : '') .
                    "Catatan Pembayaran: " . $request->payment_note;
            }

            $penggajian->update($updateData);

            Log::info('Committing transaction');
            DB::commit();

            Log::info('Payment processed successfully', [
                'penggajian_id' => $penggajian->id,
                'employee_name' => $employeeName,
                'amount' => $paymentAmount,
                'payment_method' => $request->payment_method,
                'payment_date' => $paymentDate
            ]);

            return redirect()->route('hr.penggajian.show', $penggajian->id)
                ->with('success', 'Pembayaran gaji berhasil diproses. Total: Rp ' . number_format($paymentAmount, 0, ',', '.'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found in payment processing', [
                'error' => $e->getMessage(),
                'penggajian_id' => $id
            ]);
            DB::rollBack();
            return back()->with('error', 'Data penggajian tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'penggajian_id' => $id,
                'request_data' => $request->all()
            ]);
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan dalam memproses pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export PDF slip gaji
     */
    public function exportPdf(string $id)
    {
        // Increase the execution time limit for PDF generation
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $penggajian = Penggajian::with([
            'karyawan',
            'approver'
        ])->findOrFail($id);

        // Generate QR codes for signatures
        $qrCodes = $this->generateQRCodes($penggajian);

        // Get approval information
        $approvedBy = $penggajian->approver;
        $isApproved = $penggajian->status === 'disetujui' || $penggajian->status === 'dibayar';

        // Get month name
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $monthName = $monthNames[$penggajian->bulan];

        $pdf = Pdf::loadView('hr_karyawan.penggajian_dan_tunjangan.pdf', compact(
            'penggajian',
            'qrCodes',
            'approvedBy',
            'isApproved',
            'monthName'
        ));

        $pdf->setPaper('a4');

        $action = request()->query('action', 'download');
        $filename = 'Slip-Gaji-' . $penggajian->karyawan->nama_lengkap . '-' . $monthName . '-' . $penggajian->tahun . '.pdf';

        if ($action === 'stream') {
            return $pdf->stream($filename);
        } else {
            return $pdf->download($filename);
        }
    }

    /**
     * Print PDF (stream to browser)
     */
    public function printPdf(string $id)
    {
        request()->merge(['action' => 'stream']);
        return $this->exportPdf($id);
    }

    /**
     * Generate QR codes for slip gaji PDF signatures
     */
    private function generateQRCodes($penggajian)
    {
        // Get log activities for approval
        $approvalLog = LogAktivitas::where('modul', 'penggajian')
            ->where('data_id', $penggajian->id)
            ->where(function ($query) {
                $query->where('aktivitas', 'LIKE', '%setuju%')
                    ->orWhere('aktivitas', 'LIKE', '%approve%')
                    ->orWhere('aktivitas', 'LIKE', '%bayar%');
            })
            ->first();

        $processedBy = null;
        $processedAt = null;

        if ($approvalLog) {
            $processedBy = $approvalLog->user;
            $processedAt = Carbon::parse($approvalLog->created_at);
        } elseif ($penggajian->approver) {
            $processedBy = $penggajian->approver;
            $processedAt = $penggajian->updated_at;
        }

        // Create a dummy HR user for creator QR code
        $hrUser = (object) [
            'name' => 'HR Department',
            'email' => setting('company_email', 'hr@sinarsurya.com'),
            'id' => 0,
            'created_at' => $penggajian->created_at
        ];

        return $this->generatePDFQRCodes(
            'slip_gaji',
            $penggajian->id,
            'SLIP-' . $penggajian->karyawan->nama_lengkap . '-' . $penggajian->bulan . '-' . $penggajian->tahun,
            $hrUser, // HR Department as creator
            $processedBy, // Approved by
            $processedAt, // Approved at
            [
                'karyawan' => $penggajian->karyawan->nama_lengkap,
                'periode' => $penggajian->bulan . '/' . $penggajian->tahun,
                'total_gaji' => $penggajian->total_gaji,
                'status' => $penggajian->status,
                'metode_pembayaran' => $penggajian->metode_pembayaran
            ]
        );
    }
}
