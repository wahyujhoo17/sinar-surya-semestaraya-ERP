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
use App\Models\PurchaseOrder;
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
        $statusFilter = $request->input('status');
        $showOnlyUnpaid = false;

        if ($statusFilter && $statusFilter !== '') {
            if ($statusFilter === 'belum_dibayar') {
                // Special handling for "belum_dibayar" - we'll filter this after pagination
                $showOnlyUnpaid = true;
                // Don't apply any filter to the query - we'll handle this later
            } else {
                // Filter for existing payment records
                $query->where('status', $statusFilter);
            }
        }

        // Pengurutan
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Validasi field yang diizinkan untuk sort
        $allowedSortFields = ['bulan', 'tahun', 'total_gaji', 'status', 'tanggal_bayar', 'created_at', 'karyawan_nama'];

        if (in_array($sortField, $allowedSortFields)) {
            if ($sortField === 'karyawan_nama') {
                // Sort by employee name using subquery to avoid JOIN issues
                $query->orderBy(
                    \App\Models\Karyawan::select('nama_lengkap')
                        ->whereColumn('karyawan.id', 'penggajian.karyawan_id')
                        ->limit(1),
                    $sortDirection
                );
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
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
        // Ensure perPage is a valid integer
        $perPage = max(1, min(100, (int)$perPage)); // Limit between 1 and 100

        // Get unpaid employees for the current month/year
        $unpaidEmployeesQuery = $activeEmployees->whereNotIn('id', $paidEmployeeIds);

        // Apply search filter to unpaid employees if search is present
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $unpaidEmployees = $unpaidEmployeesQuery->filter(function ($employee) use ($search) {
                return stripos($employee->nama_lengkap, $search) !== false ||
                    stripos($employee->nip, $search) !== false;
            });
        } else {
            $unpaidEmployees = $unpaidEmployeesQuery;
        }

        // Handle special case for "belum_dibayar" filter
        if ($showOnlyUnpaid) {
            // For "belum_dibayar", we need to paginate the unpaid employees
            $unpaidEmployeesCollection = $unpaidEmployees->values(); // Reset keys

            // Apply sorting to unpaid employees if needed
            if (in_array($sortField, $allowedSortFields)) {
                if ($sortField === 'karyawan_nama') {
                    $unpaidEmployeesCollection = $sortDirection === 'asc'
                        ? $unpaidEmployeesCollection->sortBy('nama_lengkap')
                        : $unpaidEmployeesCollection->sortByDesc('nama_lengkap');
                } elseif ($sortField === 'total_gaji') {
                    $unpaidEmployeesCollection = $sortDirection === 'asc'
                        ? $unpaidEmployeesCollection->sortBy('gaji_pokok')
                        : $unpaidEmployeesCollection->sortByDesc('gaji_pokok');
                }
                // Reset keys after sorting
                $unpaidEmployeesCollection = $unpaidEmployeesCollection->values();
            }

            // Create a paginator for unpaid employees
            $currentPage = $request->input('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $paginatedUnpaidEmployees = $unpaidEmployeesCollection->slice($offset, $perPage);

            // Create a LengthAwarePaginator for unpaid employees
            $penggajian = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), // Empty collection for paid employees
                0, // Total paid employees
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]
            );

            // Set the unpaid employees for display
            $unpaidEmployees = $paginatedUnpaidEmployees;

            // Create a paginator specifically for unpaid employees to show pagination
            $unpaidEmployeesPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedUnpaidEmployees,
                $unpaidEmployeesCollection->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]
            );
            $unpaidEmployeesPaginator->appends($request->query());

            // Override the penggajian paginator to use unpaid employees pagination
            $penggajian = $unpaidEmployeesPaginator;
        } else {
            // For other cases (no filter or specific status filters)
            if ($statusFilter && $statusFilter !== '') {
                // Specific status filter - only show existing payment records
                $penggajian = $query->paginate($perPage);
                $penggajian->appends($request->query());
                $unpaidEmployees = collect(); // Empty collection
            } else {
                // No status filter - create combined pagination for both paid and unpaid employees

                // Get total counts
                $totalPaidEmployees = $query->count();
                $totalUnpaidEmployees = $unpaidEmployees->count();
                $totalEmployees = $totalPaidEmployees + $totalUnpaidEmployees;

                // Calculate pagination
                $currentPage = $request->input('page', 1);
                $offset = ($currentPage - 1) * $perPage;

                // Determine how many paid and unpaid employees to show on this page
                if ($offset < $totalPaidEmployees) {
                    // We're still showing paid employees
                    $paidEmployeesToShow = min($perPage, $totalPaidEmployees - $offset);
                    $paidOffset = $offset;

                    // Get the paid employees for this page
                    $paidEmployeesForPage = $query->skip($paidOffset)->take($paidEmployeesToShow)->get();

                    // Calculate remaining slots for unpaid employees
                    $remainingSlots = $perPage - $paidEmployeesToShow;

                    if ($remainingSlots > 0 && $totalUnpaidEmployees > 0) {
                        // Apply sorting to unpaid employees
                        if ($sortField === 'karyawan_nama') {
                            $sortedUnpaidEmployees = $sortDirection === 'asc'
                                ? $unpaidEmployees->sortBy('nama_lengkap')
                                : $unpaidEmployees->sortByDesc('nama_lengkap');
                        } elseif ($sortField === 'total_gaji') {
                            $sortedUnpaidEmployees = $sortDirection === 'asc'
                                ? $unpaidEmployees->sortBy('gaji_pokok')
                                : $unpaidEmployees->sortByDesc('gaji_pokok');
                        } else {
                            $sortedUnpaidEmployees = $unpaidEmployees;
                        }

                        $unpaidEmployees = $sortedUnpaidEmployees->take($remainingSlots);
                    } else {
                        $unpaidEmployees = collect();
                    }
                } else {
                    // We're beyond all paid employees, show only unpaid employees
                    $paidEmployeesForPage = collect();
                    $unpaidOffset = $offset - $totalPaidEmployees;

                    // Apply sorting to unpaid employees
                    if ($sortField === 'karyawan_nama') {
                        $sortedUnpaidEmployees = $sortDirection === 'asc'
                            ? $unpaidEmployees->sortBy('nama_lengkap')
                            : $unpaidEmployees->sortByDesc('nama_lengkap');
                    } elseif ($sortField === 'total_gaji') {
                        $sortedUnpaidEmployees = $sortDirection === 'asc'
                            ? $unpaidEmployees->sortBy('gaji_pokok')
                            : $unpaidEmployees->sortByDesc('gaji_pokok');
                    } else {
                        $sortedUnpaidEmployees = $unpaidEmployees;
                    }

                    $unpaidEmployees = $sortedUnpaidEmployees->skip($unpaidOffset)->take($perPage);
                }

                // Create a custom paginator that represents the combined data
                $penggajian = new \Illuminate\Pagination\LengthAwarePaginator(
                    $paidEmployeesForPage ?? collect(),
                    $totalEmployees, // Total count includes both paid and unpaid
                    $perPage,
                    $currentPage,
                    [
                        'path' => $request->url(),
                        'pageName' => 'page',
                    ]
                );
                $penggajian->appends($request->query());
            }
        }

        // Handle AJAX request - only return JSON for explicit AJAX requests with our custom parameter
        $isExplicitAjaxRequest = $request->has('ajax_request') && $request->get('ajax_request') === '1';

        if ($isExplicitAjaxRequest) {
            try {
                // Debug information
                Log::info('AJAX Penggajian Request', [
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                    'statusFilter' => $statusFilter,
                    'showOnlyUnpaid' => $showOnlyUnpaid,
                    'penggajian_count' => $penggajian->count(),
                    'unpaidEmployees_count' => $unpaidEmployees->count(),
                    'total_items' => $penggajian->total(),
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
                    'first_item' => $penggajian->firstItem() ?: 0,
                    'last_item' => $penggajian->lastItem() ?: 0,
                    'current_page' => $penggajian->currentPage(),
                    'last_page' => $penggajian->lastPage(),
                    'per_page' => $penggajian->perPage(),
                    'currentMonth' => $currentMonth,
                    'currentYear' => $currentYear,
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                    'showOnlyUnpaid' => $showOnlyUnpaid,
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
        $salaryComponents = [];

        if ($karyawan_id) {
            $selectedKaryawan = Karyawan::find($karyawan_id);
            if ($selectedKaryawan) {
                $gajiPokok = $selectedKaryawan->gaji_pokok;
                $salaryComponents = [
                    'tunjangan_btn' => $selectedKaryawan->tunjangan_btn ?? 0,
                    'tunjangan_keluarga' => $selectedKaryawan->tunjangan_keluarga ?? 0,
                    'tunjangan_jabatan' => $selectedKaryawan->tunjangan_jabatan ?? 0,
                    'tunjangan_transport' => $selectedKaryawan->tunjangan_transport ?? 0,
                    'tunjangan_makan' => $selectedKaryawan->tunjangan_makan ?? 0,
                    'tunjangan_pulsa' => $selectedKaryawan->tunjangan_pulsa ?? 0,
                    'default_tunjangan' => $selectedKaryawan->default_tunjangan ?? 0,
                    'default_bonus' => $selectedKaryawan->default_bonus ?? 0,
                    'bpjs' => $selectedKaryawan->bpjs ?? 0,
                    'default_potongan' => $selectedKaryawan->default_potongan ?? 0,
                ];
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
            'salaryComponents' => $salaryComponents,
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
            'komisi' => 'nullable|numeric|min:0',
            'cash_bon' => 'nullable|numeric|min:0',
            'keterlambatan' => 'nullable|numeric|min:0',
            'bpjs_karyawan' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
            'status' => 'required|in:draft,disetujui,dibayar',
            'catatan' => 'nullable|string',
            'sales_order_adjustments' => 'nullable|array',
            'sales_order_adjustments.*.sales_order_id' => 'required|exists:sales_order,id',
            'sales_order_adjustments.*.cashback_nominal' => 'nullable|numeric|min:0',
            'sales_order_adjustments.*.overhead_persen' => 'nullable|numeric|min:0|max:100',
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

        // Hitung komisi dari sales order jika ada dengan penyesuaian per sales order
        $salesOrderAdjustments = $request->input('sales_order_adjustments', []);

        $komisiData = $this->hitungKomisiKaryawan(
            $request->karyawan_id,
            $request->bulan,
            $request->tahun,
            $salesOrderAdjustments
        );
        $komisi = $komisiData['komisi'];
        $salesOrderIds = $komisiData['salesOrderIds'];
        $komisiDetails = $komisiData['details'] ?? [];

        // Override komisi jika dikirim dari request (manual input)
        if ($request->has('komisi') && $request->komisi !== null) {
            $komisi = $request->komisi;
        }

        // Hitung pendapatan
        $pendapatan = $request->gaji_pokok +
            ($request->tunjangan ?? 0) +
            ($request->bonus ?? 0) +
            ($request->lembur ?? 0) +
            $komisi;

        // Hitung potongan
        $potongan = ($request->potongan ?? 0) +
            ($request->cash_bon ?? 0) +
            ($request->keterlambatan ?? 0) +
            ($request->bpjs_karyawan ?? 0);

        // Total gaji (bruto)
        $totalGaji = $pendapatan - $potongan;

        // THP (Take Home Pay) sama dengan total gaji dalam hal ini
        $thp = $totalGaji;

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
                'komisi' => $komisi,
                'cash_bon' => $request->cash_bon ?? 0,
                'keterlambatan' => $request->keterlambatan ?? 0,
                'bpjs_karyawan' => $request->bpjs_karyawan ?? 0,
                'total_gaji' => $totalGaji,
                'thp' => $thp,
                'tanggal_bayar' => $request->tanggal_bayar,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'disetujui_oleh' => $request->status == 'disetujui' ? Auth::id() : null,
            ]);

            // Tambahkan komponen komisi per sales order jika ada
            if (!empty($komisiDetails)) {
                foreach ($komisiDetails as $detail) {
                    $salesOrder = \App\Models\SalesOrder::find($detail['sales_order_id']);
                    $keteranganKomisi = 'Komisi dari SO: ' . ($salesOrder ? $salesOrder->nomor : $detail['sales_order_id']);

                    // Tambahkan informasi penyesuaian jika ada
                    $adjustments = [];
                    if ($detail['cashback_nominal'] > 0) {
                        $adjustments[] = "Cashback: Rp " . number_format($detail['cashback_nominal'], 0, ',', '.');
                    }
                    if ($detail['overhead_persen'] > 0) {
                        $adjustments[] = "Overhead: {$detail['overhead_persen']}%";
                    }

                    if (!empty($adjustments)) {
                        $keteranganKomisi .= ' [' . implode(', ', $adjustments) . ']';
                    }

                    KomponenGaji::create([
                        'penggajian_id' => $penggajian->id,
                        'nama_komponen' => 'Komisi Penjualan',
                        'jenis' => 'pendapatan',
                        'nilai' => $detail['komisi'],
                        'keterangan' => $keteranganKomisi,
                        'sales_order_id' => $detail['sales_order_id'],
                        'cashback_nominal' => $detail['cashback_nominal'],
                        'overhead_persen' => $detail['overhead_persen'],
                        'netto_penjualan_original' => $detail['netto_penjualan_original'],
                        'netto_beli_original' => $detail['netto_beli_original'],
                        'netto_penjualan_adjusted' => $detail['netto_penjualan_adjusted'],
                        'netto_beli_adjusted' => $detail['netto_beli_adjusted'],
                        'margin_persen' => $detail['margin_persen'],
                        'komisi_rate' => $detail['komisi_rate'],
                        'product_details' => $detail['product_details'] ?? [],
                        'sales_ppn' => $detail['sales_ppn'] ?? null,
                        'has_sales_ppn' => $detail['has_sales_ppn'] ?? false
                    ]);
                }
            }

            // Simpan komponen gaji lainnya jika ada
            if ($request->has('komponenGaji')) {
                foreach ($request->komponenGaji as $komponen) {
                    KomponenGaji::create([
                        'penggajian_id' => $penggajian->id,
                        'nama_komponen' => $komponen['nama_komponen'],
                        'jenis' => $komponen['jenis'],
                        'nilai' => $komponen['nilai'],
                        'keterangan' => $komponen['keterangan'] ?? null,
                        'cashback_persen' => $komponen['cashback_persen'] ?? 0,
                        'overhead_persen' => $komponen['overhead_persen'] ?? 0
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
        $penggajian = Penggajian::with(['karyawan', 'approver', 'komponenGaji.salesOrder.customer'])->findOrFail($id);

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
    private function hitungKomisiKaryawan($karyawanId, $bulan, $tahun, $salesOrderAdjustments = [])
    {
        // Get the user_id associated with karyawan
        $karyawan = Karyawan::findOrFail($karyawanId);
        if (!$karyawan->user_id) {
            return ['komisi' => 0, 'salesOrderIds' => []]; // Karyawan tidak terkait dengan user, tidak bisa menghitung komisi
        }

        $userId = $karyawan->user_id;

        // Ambil semua sales order milik customer yang sales_id-nya sama dengan user ini, sudah LUNAS dan dibayar pada bulan/tahun tertentu
        $salesOrders = SalesOrder::with(['customer'])
            ->where('status_pembayaran', 'lunas')
            ->whereHas('customer', function ($query) use ($userId) {
                $query->where('sales_id', $userId);
            })
            ->whereHas('invoices.pembayaranPiutang', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            })
            ->get();

        Log::info('sales order milik customer', ['ids' => $salesOrders]);

        $totalKomisi = 0;
        $processedSalesOrderIds = [];
        $komisiDetails = [];

        // Convert adjustments array for easier lookup
        $adjustmentsByOrderId = [];
        foreach ($salesOrderAdjustments as $adjustment) {
            $adjustmentsByOrderId[$adjustment['sales_order_id']] = $adjustment;
        }

        // Cek apakah sudah ada komisi yang dihitung pada periode sebelumnya
        $existingKomisi = Penggajian::where('karyawan_id', $karyawanId)
            ->where(function ($query) use ($bulan, $tahun) {
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

        // Dapatkan semua sales order yang sudah pernah dihitung komisinya
        $alreadyProcessedSalesOrderIds = [];
        if (!empty($existingKomisi)) {
            $alreadyProcessedSalesOrderIds = KomponenGaji::whereIn('penggajian_id', $existingKomisi)
                ->where('nama_komponen', 'like', '%Komisi Penjualan%')
                ->whereNotNull('sales_order_id')
                ->pluck('sales_order_id')
                ->toArray();
        }

        Log::info('alreadyProcessedSalesOrderIds', ['ids' => $alreadyProcessedSalesOrderIds]);

        foreach ($salesOrders as $order) {
            // Skip if this sales order has already been processed
            if (in_array($order->id, $alreadyProcessedSalesOrderIds)) {
                continue;
            }

            // Get adjustments for this specific sales order
            $adjustment = $adjustmentsByOrderId[$order->id] ?? [];
            $cashbackNominal = $adjustment['cashback_nominal'] ?? 0;
            $overheadPersen = $adjustment['overhead_persen'] ?? 0;

            $details = SalesOrderDetail::where('sales_order_id', $order->id)->get();

            // Check if sales order has PPN
            $salesPpn = $order->ppn ?? 0;
            $hasSalesPpn = $salesPpn > 0;

            // Use subtotal from SO (which is already EXCLUDE PPN and includes any discounts)
            // Subtotal in sales_order table = harga items - diskon (BEFORE adding PPN)
            // Total = subtotal + PPN
            $totalNettoPenjualanOriginal = floatval($order->subtotal ?? 0);

            Log::info('Processing SO for commission', [
                'order_id' => $order->id,
                'order_nomor' => $order->nomor,
                'subtotal' => $totalNettoPenjualanOriginal,
                'sales_ppn' => $salesPpn,
                'has_sales_ppn' => $hasSalesPpn,
                'total' => $order->total
            ]);

            $totalNettoPenjualan = 0;
            $totalNettoBeli = 0;
            $productDetails = [];

            // Calculate per product with PPN rules
            foreach ($details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if (!$produk) {
                    continue;
                }

                // Get last purchase order for this product to check purchase PPN
                $lastPO = PurchaseOrder::join('purchase_order_detail', 'purchase_order.id', '=', 'purchase_order_detail.po_id')
                    ->where('purchase_order_detail.produk_id', $produk->id)
                    ->where('purchase_order.status', 'selesai')
                    ->orderBy('purchase_order.tanggal', 'desc')
                    ->select('purchase_order.*')
                    ->first();

                $purchasePpn = $lastPO ? ($lastPO->ppn ?? 0) : 0;
                $hasPurchasePpn = $purchasePpn > 0;

                // Calculate netto jual for this item (already exclude PPN from SO subtotal)
                // detail->subtotal already excludes PPN because SO->subtotal excludes PPN
                $nettoJualItem = floatval($detail->subtotal ?? 0);

                // Calculate netto beli (purchase value)
                // harga_beli in produk table should be the price we paid (include PPN if we paid PPN)
                $nettoBeliItem = $produk->harga_beli * $detail->quantity;

                // Apply 3 PPN Rules
                $ppnRule = 'none';
                $nettoJualAdjusted = $nettoJualItem;
                $nettoBeliAdjusted = $nettoBeliItem;

                // Rule 1: Sales PPN + Purchase PPN
                // Pembelian PPN dan Penjualan PPN
                // Dihitung dari harga non-PPN keduanya atau harga sudah PPN karena akan sama saja
                // Sales subtotal already exclude PPN, Purchase include PPN → convert purchase to exclude PPN
                if ($hasSalesPpn && $hasPurchasePpn) {
                    $ppnRule = 'rule_1';
                    // Convert purchase to exclude PPN for fair comparison
                    $nettoBeliAdjusted = $nettoBeliItem / (1 + $purchasePpn / 100);
                }
                // Rule 2: Sales PPN + Purchase Non-PPN
                // Pembelian Non-PPN dan Penjualan PPN
                // Penjualan dihitung dari harga non-PPN (sudah exclude PPN di subtotal)
                elseif ($hasSalesPpn && !$hasPurchasePpn) {
                    $ppnRule = 'rule_2';
                    // Sales already exclude PPN (subtotal), Purchase already exclude PPN
                    // No adjustment needed - both already exclude PPN
                }
                // Rule 3: Sales Non-PPN + Purchase PPN
                // Pembelian PPN dan Penjualan Non-PPN
                // Pembelian dihitung dari harga INCLUDE PPN (tidak disesuaikan)
                elseif (!$hasSalesPpn && $hasPurchasePpn) {
                    $ppnRule = 'rule_3';
                    // Keep purchase price as is (include PPN)
                    // No adjustment needed
                }

                $totalNettoPenjualan += $nettoJualAdjusted;
                $totalNettoBeli += $nettoBeliAdjusted;

                // Calculate margin for this product
                $productMargin = 0;
                if ($nettoBeliAdjusted > 0) {
                    $productMargin = (($nettoJualAdjusted - $nettoBeliAdjusted) / $nettoBeliAdjusted) * 100;
                }

                // Store product detail
                $productDetails[] = [
                    'produk_id' => $produk->id,
                    'kode_produk' => $produk->kode,
                    'nama_produk' => $produk->nama,
                    'quantity' => $detail->quantity,
                    'harga_jual' => $nettoJualItem,
                    'harga_beli' => $nettoBeliItem,
                    'harga_jual_adjusted' => $nettoJualAdjusted,
                    'harga_beli_adjusted' => $nettoBeliAdjusted,
                    'sales_ppn' => $salesPpn,
                    'purchase_ppn' => $purchasePpn,
                    'has_sales_ppn' => $hasSalesPpn,
                    'has_purchase_ppn' => $hasPurchasePpn,
                    'ppn_rule' => $ppnRule,
                    'margin_persen' => $productMargin
                ];

                Log::info('Product PPN calculation', [
                    'produk' => $produk->kode . ' - ' . $produk->nama,
                    'sales_ppn' => $salesPpn,
                    'purchase_ppn' => $purchasePpn,
                    'ppn_rule' => $ppnRule,
                    'netto_jual_original' => $nettoJualItem,
                    'netto_beli_original' => $nettoBeliItem,
                    'netto_jual_adjusted' => $nettoJualAdjusted,
                    'netto_beli_adjusted' => $nettoBeliAdjusted,
                    'margin' => $productMargin
                ]);
            }

            // Apply penyesuaian untuk sales order ini (cashback & overhead)
            $nettoPenjualanAdjusted = $totalNettoPenjualan - $cashbackNominal;
            $nettoBeliAdjusted = $totalNettoBeli * (1 + $overheadPersen / 100);

            if ($nettoBeliAdjusted > 0 && $nettoPenjualanAdjusted > 0) {
                // Hitung margin berdasarkan nilai yang sudah disesuaikan
                $marginPersen = (($nettoPenjualanAdjusted - $nettoBeliAdjusted) / $nettoBeliAdjusted) * 100;

                // Cari tier persentase komisi
                $komisiRate = $this->getKomisiRateByMargin($marginPersen);

                // Hitung komisi untuk sales order ini
                $orderKomisi = $nettoPenjualanAdjusted * ($komisiRate / 100);

                Log::info('Commission calculation for order', [
                    'order_id' => $order->id,
                    'total_netto_penjualan' => $totalNettoPenjualan,
                    'total_netto_beli' => $totalNettoBeli,
                    'cashback' => $cashbackNominal,
                    'overhead_persen' => $overheadPersen,
                    'netto_penjualan_adjusted' => $nettoPenjualanAdjusted,
                    'netto_beli_adjusted' => $nettoBeliAdjusted,
                    'margin_persen' => $marginPersen,
                    'komisi_rate' => $komisiRate,
                    'order_komisi' => $orderKomisi
                ]);

                if ($orderKomisi > 0) {
                    $totalKomisi += $orderKomisi;
                    $processedSalesOrderIds[] = $order->id;

                    // Simpan detail untuk penyimpanan komponen gaji
                    $komisiDetails[] = [
                        'sales_order_id' => $order->id,
                        'komisi' => $orderKomisi,
                        'cashback_nominal' => $cashbackNominal,
                        'overhead_persen' => $overheadPersen,
                        'netto_penjualan_original' => $totalNettoPenjualanOriginal,
                        'netto_beli_original' => $totalNettoBeli,
                        'netto_penjualan_adjusted' => $nettoPenjualanAdjusted,
                        'netto_beli_adjusted' => $nettoBeliAdjusted,
                        'margin_persen' => $marginPersen,
                        'komisi_rate' => $komisiRate,
                        'product_details' => $productDetails,
                        'sales_ppn' => $salesPpn,
                        'has_sales_ppn' => $hasSalesPpn
                    ];
                }
            }
        }

        Log::info('Processed Sales Order IDs', ['ids' => $processedSalesOrderIds]);

        return [
            'komisi' => $totalKomisi,
            'salesOrderIds' => $processedSalesOrderIds,
            'details' => $komisiDetails
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
            ['min' => 18.00, 'max' => 20.49, 'rate' => 1.00],
            ['min' => 20.50, 'max' => 25.49, 'rate' => 1.25],
            ['min' => 25.50, 'max' => 30.49, 'rate' => 1.50],
            ['min' => 30.50, 'max' => 35.49, 'rate' => 1.75],
            ['min' => 35.50, 'max' => 40.49, 'rate' => 2.00],
            ['min' => 40.50, 'max' => 45.49, 'rate' => 2.25],
            ['min' => 45.50, 'max' => 50.49, 'rate' => 2.50],
            ['min' => 50.50, 'max' => 55.49, 'rate' => 2.75],
            ['min' => 55.50, 'max' => 60.49, 'rate' => 3.00],
            ['min' => 60.50, 'max' => 65.49, 'rate' => 3.25],
            ['min' => 65.50, 'max' => 70.49, 'rate' => 3.50],
            ['min' => 70.50, 'max' => 75.49, 'rate' => 3.75],
            ['min' => 75.50, 'max' => 80.49, 'rate' => 4.00],
            ['min' => 80.50, 'max' => 85.49, 'rate' => 4.25],
            ['min' => 85.50, 'max' => 90.49, 'rate' => 4.50],
            ['min' => 90.50, 'max' => 95.49, 'rate' => 4.75],
            ['min' => 95.50, 'max' => 100.49, 'rate' => 5.00],
            ['min' => 101.00, 'max' => 110.99, 'rate' => 5.25],
            ['min' => 111.00, 'max' => 125.99, 'rate' => 5.50],
            ['min' => 126.00, 'max' => 140.99, 'rate' => 5.75],
            ['min' => 141.00, 'max' => 160.99, 'rate' => 6.00],
            ['min' => 161.00, 'max' => 180.99, 'rate' => 6.25],
            ['min' => 181.00, 'max' => 200.99, 'rate' => 6.50],
            ['min' => 201.00, 'max' => 225.99, 'rate' => 7.00],
            ['min' => 226.00, 'max' => 250.99, 'rate' => 7.25],
            ['min' => 251.00, 'max' => 275.99, 'rate' => 7.50],
            ['min' => 276.00, 'max' => 300.99, 'rate' => 8.00],
            ['min' => 301.00, 'max' => 325.99, 'rate' => 8.25],
            ['min' => 326.00, 'max' => 350.99, 'rate' => 8.50],
            ['min' => 351.00, 'max' => 400.99, 'rate' => 9.00],
            ['min' => 401.00, 'max' => 450.99, 'rate' => 9.50],
            ['min' => 451.00, 'max' => 500.99, 'rate' => 10.00],
            ['min' => 501.00, 'max' => 600.99, 'rate' => 10.50],
            ['min' => 601.00, 'max' => 700.99, 'rate' => 11.00],
            ['min' => 701.00, 'max' => 800.99, 'rate' => 11.50],
            ['min' => 801.00, 'max' => 900.99, 'rate' => 12.00],
            ['min' => 901.00, 'max' => 1000.99, 'rate' => 12.50],
            ['min' => 1001.00, 'max' => 1100.99, 'rate' => 13.00],
            ['min' => 1101.00, 'max' => 1200.99, 'rate' => 13.50],
            ['min' => 1201.00, 'max' => 1300.99, 'rate' => 14.00],
            ['min' => 1301.00, 'max' => 1400.99, 'rate' => 14.50],
            ['min' => 1401.00, 'max' => 1500.99, 'rate' => 15.00],
            ['min' => 1501.00, 'max' => 1600.99, 'rate' => 15.50],
            ['min' => 1601.00, 'max' => 1700.99, 'rate' => 16.00],
            ['min' => 1701.00, 'max' => 1800.99, 'rate' => 16.50],
            ['min' => 1801.00, 'max' => 1900.99, 'rate' => 17.00],
            ['min' => 1901.00, 'max' => 2000.99, 'rate' => 17.50],
            ['min' => 2001.00, 'max' => 2100.99, 'rate' => 18.00],
            ['min' => 2101.00, 'max' => 2200.99, 'rate' => 18.50],
            ['min' => 2201.00, 'max' => 2300.99, 'rate' => 19.00],
            ['min' => 2301.00, 'max' => 2400.99, 'rate' => 19.50],
            ['min' => 2401.00, 'max' => 2501.99, 'rate' => 20.00],
            ['min' => 2501.00, 'max' => 2600.99, 'rate' => 20.50],
            ['min' => 2601.00, 'max' => 2700.99, 'rate' => 21.00],
            ['min' => 2701.00, 'max' => 2800.99, 'rate' => 21.50],
            ['min' => 2801.00, 'max' => 2900.99, 'rate' => 22.00],
            ['min' => 2901.00, 'max' => 3000.99, 'rate' => 22.50],
            ['min' => 3001.00, 'max' => 3100.99, 'rate' => 23.00],
            ['min' => 3101.00, 'max' => 3200.99, 'rate' => 23.50],
            ['min' => 3201.00, 'max' => 3300.99, 'rate' => 24.00],
            ['min' => 3301.00, 'max' => 3400.99, 'rate' => 24.50],
            ['min' => 3401.00, 'max' => 3500.99, 'rate' => 25.00],
            ['min' => 3501.00, 'max' => 3600.99, 'rate' => 25.50],
            ['min' => 3601.00, 'max' => 3700.99, 'rate' => 26.00],
            ['min' => 3701.00, 'max' => 3800.99, 'rate' => 26.50],
            ['min' => 3801.00, 'max' => 3900.99, 'rate' => 27.00],
            ['min' => 3901.00, 'max' => 4000.99, 'rate' => 27.50],
            ['min' => 4001.00, 'max' => 4100.99, 'rate' => 28.00],
            ['min' => 4101.00, 'max' => 4200.99, 'rate' => 28.50],
            ['min' => 4201.00, 'max' => 4300.99, 'rate' => 29.00],
            ['min' => 4301.00, 'max' => 4400.99, 'rate' => 29.50],
            ['min' => 4401.00, 'max' => 4500.99, 'rate' => 30.00],
        ];

        // Cari tier yang sesuai dengan margin
        foreach ($komisiTiers as $tier) {
            if ($marginPersen >= $tier['min'] && $marginPersen <= $tier['max']) {
                return $tier['rate'];
            }
        }

        // Jika margin di atas tier tertinggi (4500%), gunakan rate maksimum (30%)
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
            'sales_order_adjustments' => 'nullable|array',
            'sales_order_adjustments.*.sales_order_id' => 'required|exists:sales_order,id',
            'sales_order_adjustments.*.cashback_nominal' => 'nullable|numeric|min:0',
            'sales_order_adjustments.*.overhead_persen' => 'nullable|numeric|min:0|max:100',
        ]);

        $salesOrderAdjustments = $request->input('sales_order_adjustments', []);

        // Log request parameters
        Log::info('Calculating commission', [
            'karyawan_id' => $request->karyawan_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'sales_order_adjustments' => $salesOrderAdjustments
        ]);

        $komisiData = $this->hitungKomisiKaryawan($request->karyawan_id, $request->bulan, $request->tahun, $salesOrderAdjustments);
        $komisi = $komisiData['komisi'];
        $salesOrderIds = $komisiData['salesOrderIds'];


        // Log sales order IDs found
        Log::info('Sales orders found for commission', [
            'count' => count($salesOrderIds),
            'ids' => $salesOrderIds
        ]);

        // Get detailed sales order data menggunakan data yang sama dari hitungKomisiKaryawan
        $salesOrderDetails = [];
        if (!empty($komisiData['details'])) {
            // Ambil detail komisi yang sudah dihitung dengan benar
            $detailsById = collect($komisiData['details'])->keyBy('sales_order_id');

            // Get sales order information untuk display
            $salesOrders = SalesOrder::with(['customer', 'details.produk'])
                ->whereIn('id', $salesOrderIds)
                ->get();

            Log::info('Found sales orders', ['count' => $salesOrders->count()]);

            foreach ($salesOrders as $order) {
                $detail = $detailsById[$order->id] ?? null;

                if ($detail) {
                    // Gunakan data yang sudah dihitung dengan benar dari hitungKomisiKaryawan
                    $productNames = $order->details->pluck('produk.nama')->filter()->toArray();

                    // Debug log untuk cek nilai
                    Log::info('Sales Order Detail for response', [
                        'so_nomor' => $order->nomor,
                        'netto_penjualan_original' => $detail['netto_penjualan_original'],
                        'netto_penjualan_adjusted' => $detail['netto_penjualan_adjusted'],
                        'netto_beli_original' => $detail['netto_beli_original'],
                        'margin_persen' => $detail['margin_persen'],
                        'komisi_rate' => $detail['komisi_rate'],
                        'komisi' => $detail['komisi']
                    ]);

                    $salesOrderDetails[] = [
                        'id' => $order->id,
                        'nomor' => $order->nomor,
                        'tanggal' => $order->tanggal,
                        'customer' => $order->customer ? ($order->customer->company ?? $order->customer->nama) : 'N/A',
                        'produk' => implode(', ', array_slice($productNames, 0, 3)) . (count($productNames) > 3 ? '...' : ''),
                        'harga_jual' => $detail['netto_penjualan_original'],
                        'harga_beli' => $detail['netto_beli_original'],
                        'margin' => $detail['netto_penjualan_original'] - $detail['netto_beli_original'],
                        'margin_persen' => round($detail['margin_persen'], 2),
                        'commission_rate' => $detail['komisi_rate'],
                        'komisi' => $detail['komisi'],
                        'product_details' => $detail['product_details'] ?? [],
                        'sales_ppn' => $detail['sales_ppn'] ?? null,
                        'has_sales_ppn' => $detail['has_sales_ppn'] ?? false
                    ];
                }
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

    /**
     * Helper method to get sort key for combined sorting
     */
    private function getSortKey($employee, $sortField, $type)
    {
        switch ($sortField) {
            case 'karyawan_nama':
                return $type === 'paid' ? $employee->karyawan->nama_lengkap : $employee->nama_lengkap;
            case 'total_gaji':
                return $type === 'paid' ? $employee->total_gaji : $employee->gaji_pokok;
            case 'status':
                return $type === 'paid' ? $employee->status : 'belum_dibayar';
            case 'tanggal_bayar':
                return $type === 'paid' ? $employee->tanggal_bayar : null;
            case 'bulan':
                return $type === 'paid' ? $employee->bulan : 0;
            case 'tahun':
                return $type === 'paid' ? $employee->tahun : 0;
            case 'created_at':
            default:
                return $type === 'paid' ? $employee->created_at : Carbon::now();
        }
    }
}
