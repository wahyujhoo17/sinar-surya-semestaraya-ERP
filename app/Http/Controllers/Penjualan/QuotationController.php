<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    private function generateNewQuotationNumber()
    {
        $prefix = 'QO-';
        $date = now()->format('Ymd');

        $lastQuotation = DB::table('quotation')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastQuotation && !is_null($lastQuotation->last_num)) {
            $newNumberSuffix = str_pad($lastQuotation->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }
    public function index(Request $request)
    {
        $query = null;

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan')) {
            $query = Quotation::query();
        } else {
            $query = Quotation::where('user_id', Auth::id());
        }

        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');

        // Map frontend sort field names to database column names
        $sortMapping = [
            'no' => 'nomor',
            'no_quotation' => 'nomor',
            'tanggal' => 'tanggal',
            'customer' => 'customer_id',
            'kontak' => 'customer_id',
            'status' => 'status',
            'total' => 'total'
        ];

        // Get the actual database column name to sort by
        $dbSortField = $sortMapping[$sort] ?? $sort;

        $validSorts = ['nomor', 'tanggal', 'status', 'total'];

        if (in_array($dbSortField, $validSorts) || Schema::hasColumn('quotation', $dbSortField)) {
            $query->orderBy($dbSortField, $direction);
        } elseif ($sort === 'customer' || $sort === 'kontak') {
            // Join with customer table to sort by customer name
            $query->leftJoin('customer', 'quotation.customer_id', '=', 'customer.id')
                ->orderBy('customer.nama', $direction)
                ->select('quotation.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q_customer) use ($search) {
                        $q_customer->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });

                if (is_numeric($search)) {
                    $q->orWhere('total', '=', $search);
                }
            });
        }

        if ($request->filled('status') && $request->status !== 'all' && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Add date filtering
        // Always check tanggal_awal and tanggal_akhir directly first (they take precedence)
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // If periode is set but no explicit dates, calculate the date range
        elseif ($request->filled('periode') && $request->periode !== 'custom' && !$request->filled('tanggal_awal')) {
            $today = now();
            $startDate = null;
            $endDate = $today;

            switch ($request->periode) {
                case 'today':
                    $startDate = $today->copy();
                    break;
                case 'yesterday':
                    $startDate = $today->copy()->subDay();
                    $endDate = $startDate->copy();
                    break;
                case 'this_week':
                    $startDate = $today->copy()->startOfWeek();
                    break;
                case 'last_week':
                    $startDate = $today->copy()->subWeek()->startOfWeek();
                    $endDate = $today->copy()->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = $today->copy()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = $today->copy()->subMonth()->startOfMonth();
                    $endDate = $today->copy()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = $today->copy()->startOfYear();
                    break;
            }

            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
                    ->whereDate('tanggal', '<=', $endDate->format('Y-m-d'));
            }
        }

        try {
            $quotations = $query->paginate(10)->withQueryString();

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => view('penjualan.quotation._table', compact('quotations', 'sort', 'direction'))->render(),
                    'pagination_html' => $quotations->links()->toHtml(),
                    'sort_field' => $sort,
                    'sort_direction' => $direction,
                ]);
            }

            return view('penjualan.quotation.index', compact('quotations', 'sort', 'direction'));
        } catch (\Exception $e) {
            Log::error('Error in quotation index: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            $userFriendlyMessage = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';

            // Add more context for certain types of errors
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                $userFriendlyMessage = 'Terjadi kesalahan pada database. Hubungi admin untuk bantuan.';
            }

            if (app()->environment('local', 'development', 'staging')) {
                $userFriendlyMessage .= ' Detail: ' . $e->getMessage();
            }

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan muat ulang halaman ini.</td></tr>',
                    'pagination_html' => '',
                    'error' => $userFriendlyMessage
                ], 500);
            }

            return view('penjualan.quotation.index', [
                'quotations' => collect([]),
                'sort' => $sort,
                'direction' => $direction,
                'error' => $userFriendlyMessage
            ]);
        }
    }
    public function create()
    {
        // dd('Create Quotation');
        $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        $products = Produk::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
        $nomor = $this->generateNewQuotationNumber();
        $tanggal = now()->format('Y-m-d');
        $tanggal_berlaku = now()->addDays(30)->format('Y-m-d');
        $statuses = [
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'kedaluwarsa' => 'Kedaluwarsa'
        ];

        return view('penjualan.quotation.create', compact('customers', 'products', 'satuans', 'nomor', 'tanggal', 'tanggal_berlaku', 'statuses'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'nomor' => 'required|string|unique:quotation,nomor',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'tanggal_valid_hingga' => 'nullable|date', // Matches form field name
            'status' => 'required|string|in:draft,dikirim,disetujui,ditolak,kedaluwarsa',
            'catatan' => 'nullable|string',
            'syarat_pembayaran' => 'nullable|string', // Matches form field name
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.kuantitas' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.deskripsi' => 'nullable|string', // Added deskripsi field
            'items.*.diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
        ]);

        // dd($request->all());

        try {
            DB::beginTransaction();

            $items = $request->items;
            $subtotal = 0;

            // Calculate subtotal
            foreach ($items as $item) {
                $productTotal = $item['harga'] * $item['kuantitas'];
                $discountValue = 0;

                if (isset($item['diskon_persen']) && $item['diskon_persen'] > 0) {
                    $discountValue = ($item['diskon_persen'] / 100) * $productTotal;
                }

                $subtotal += $productTotal - $discountValue;
            }

            // Calculate discounts and taxes
            $diskonGlobalPersen = $request->diskon_global_persen ?? 0;
            $diskonGlobalNominal = $request->diskon_global_nominal ?? 0;

            if ($diskonGlobalPersen > 0) {
                $diskonGlobalNominal = ($diskonGlobalPersen / 100) * $subtotal;
            }

            $afterDiscount = $subtotal - $diskonGlobalNominal;


            $ppn = $request->ppn ?? 0;
            $ppnValue = ($ppn / 100) * $afterDiscount;
            $total = $afterDiscount + $ppnValue;

            // Create Quotation
            $quotation = new Quotation();
            $quotation->nomor = $request->nomor;
            $quotation->tanggal = $request->tanggal;
            $quotation->customer_id = $request->customer_id;
            $quotation->user_id = Auth::id();
            $quotation->subtotal = $subtotal;
            $quotation->diskon_persen = $diskonGlobalPersen;
            $quotation->diskon_nominal = $diskonGlobalNominal;
            $quotation->ppn = $ppn;
            $quotation->total = $total;
            $quotation->status = $request->status;
            $quotation->tanggal_berlaku = $request->tanggal_valid_hingga;
            $quotation->catatan = $request->catatan;
            $quotation->syarat_ketentuan = $request->syarat_pembayaran;
            $quotation->save();

            // Create Quotation Details
            foreach ($items as $item) {
                $productTotal = $item['harga'] * $item['kuantitas'];
                $diskonPersenItem = $item['diskon_persen'] ?? 0;
                $diskonNominalItem = 0;

                if ($diskonPersenItem > 0) {
                    $diskonNominalItem = ($diskonPersenItem / 100) * $productTotal;
                }

                $subtotalItem = $productTotal - $diskonNominalItem;

                $quotationDetail = new QuotationDetail();
                $quotationDetail->quotation_id = $quotation->id;
                $quotationDetail->produk_id = $item['produk_id'];
                $quotationDetail->deskripsi = $item['deskripsi'] ?? null;
                $quotationDetail->quantity = $item['kuantitas'];
                $quotationDetail->satuan_id = $item['satuan_id'];
                $quotationDetail->harga = $item['harga'];
                $quotationDetail->diskon_persen = $diskonPersenItem;
                $quotationDetail->diskon_nominal = $diskonNominalItem;
                $quotationDetail->subtotal = $subtotalItem;
                $quotationDetail->save();
            }

            // Tambahkan log aktivitas untuk pembuatan quotation
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'create',
                'modul' => 'quotation',
                'data_id' => $quotation->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $quotation->nomor,
                    'customer' => $quotation->customer->nama ?? $quotation->customer->company ?? 'Customer tidak ditemukan',
                    'total' => $quotation->total
                ])
            ]);

            DB::commit();

            return redirect()->route('penjualan.quotation.index')
                ->with('success', 'Quotation berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating quotation: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat quotation. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan', 'logAktivitas.user'])->findOrFail($id);

        return view('penjualan.quotation.show', compact('quotation'));
    }

    public function edit($id)
    {
        $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan'])->findOrFail($id);
        $customers = Customer::orderBy('nama', 'asc')->get();
        $products = Produk::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
        $statuses = [
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'kedaluwarsa' => 'Kedaluwarsa'
        ];

        // Check status, only draft can be edited
        if ($quotation->status !== 'draft') {
            return redirect()->route('penjualan.quotation.index')
                ->with('error', 'Hanya quotation dengan status Draft yang dapat diedit');
        }

        return view('penjualan.quotation.edit', compact('quotation', 'customers', 'products', 'satuans', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        // Check status, only draft can be updated
        if ($quotation->status !== 'draft') {
            return redirect()->route('penjualan.quotation.index')
                ->with('error', 'Hanya quotation dengan status Draft yang dapat diupdate');
        }

        $request->validate([
            'nomor' => 'required|string|unique:quotation,nomor,' . $id . ',id',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'tanggal_berlaku' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.kuantitas' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.diskon_persen_item' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data lama untuk log
            $oldData = [
                'nomor' => $quotation->nomor,
                'customer_id' => $quotation->customer_id,
                'customer' => $quotation->customer->nama ?? $quotation->customer->company ?? 'Customer tidak ditemukan',
                'total' => $quotation->total
            ];

            $items = $request->items;
            $subtotal = 0;

            // Calculate subtotal
            foreach ($items as $item) {
                $productTotal = $item['harga'] * $item['kuantitas'];
                $discountValue = 0;

                if (isset($item['diskon_persen_item']) && $item['diskon_persen_item'] > 0) {
                    $discountValue = ($item['diskon_persen_item'] / 100) * $productTotal;
                } elseif (isset($item['diskon_nominal_item']) && $item['diskon_nominal_item'] > 0) {
                    $discountValue = $item['diskon_nominal_item'];
                }

                $subtotal += $productTotal - $discountValue;
            }

            // Calculate discounts and taxes
            $diskonPersen = $request->diskon_persen ?? 0;
            $diskonNominal = $request->diskon_nominal ?? 0;

            if ($diskonPersen > 0) {
                $diskonNominal = ($diskonPersen / 100) * $subtotal;
            }

            $afterDiscount = $subtotal - $diskonNominal;
            $ppn = $request->ppn ?? 0;
            $ppnValue = ($ppn / 100) * $afterDiscount;
            $total = $afterDiscount + $ppnValue;

            // Update Quotation
            $quotation->nomor = $request->nomor;
            $quotation->tanggal = $request->tanggal;
            $quotation->customer_id = $request->customer_id;
            $quotation->subtotal = $subtotal;
            $quotation->diskon_persen = $diskonPersen;
            $quotation->diskon_nominal = $diskonNominal;
            $quotation->ppn = $ppn;
            $quotation->total = $total;
            $quotation->tanggal_berlaku = $request->tanggal_berlaku;
            $quotation->catatan = $request->catatan;
            $quotation->syarat_ketentuan = $request->syarat_ketentuan;
            $quotation->save();

            // Delete existing details and create new ones
            QuotationDetail::where('quotation_id', $quotation->id)->delete();

            // Create Quotation Details
            foreach ($items as $item) {
                $productTotal = $item['harga'] * $item['kuantitas'];
                $diskonPersenItem = $item['diskon_persen_item'] ?? 0;
                $diskonNominalItem = 0;

                if ($diskonPersenItem > 0) {
                    $diskonNominalItem = ($diskonPersenItem / 100) * $productTotal;
                }

                $subtotalItem = $productTotal - $diskonNominalItem;

                $quotationDetail = new QuotationDetail();
                $quotationDetail->quotation_id = $quotation->id;
                $quotationDetail->produk_id = $item['produk_id'];
                $quotationDetail->deskripsi = $item['deskripsi'] ?? null;
                $quotationDetail->quantity = $item['kuantitas'];
                $quotationDetail->satuan_id = $item['satuan_id'];
                $quotationDetail->harga = $item['harga'];
                $quotationDetail->diskon_persen = $diskonPersenItem;
                $quotationDetail->diskon_nominal = $diskonNominalItem;
                $quotationDetail->subtotal = $subtotalItem;
                $quotationDetail->save();
            }

            // Tambahkan log aktivitas untuk update
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'update',
                'modul' => 'quotation',
                'data_id' => $quotation->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'before' => $oldData,
                    'after' => [
                        'nomor' => $quotation->nomor,
                        'customer_id' => $quotation->customer_id,
                        'customer' => $quotation->customer->nama ?? $quotation->customer->company ?? 'Customer tidak ditemukan',
                        'total' => $quotation->total
                    ]
                ])
            ]);

            DB::commit();

            return redirect()->route('penjualan.quotation.index')
                ->with('success', 'Quotation berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating quotation: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate quotation. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);

            // Check if the quotation has sales orders
            if ($quotation->salesOrders()->exists()) {
                return redirect()->route('penjualan.quotation.index')
                    ->with('error', 'Tidak dapat menghapus quotation karena sudah memiliki sales order terkait.');
            }

            // Check status, only draft can be deleted
            if ($quotation->status !== 'draft') {
                return redirect()->route('penjualan.quotation.index')
                    ->with('error', 'Hanya quotation dengan status Draft yang dapat dihapus');
            }

            // Simpan data untuk log sebelum dihapus
            $quotationData = [
                'id' => $quotation->id,
                'nomor' => $quotation->nomor,
                'customer' => $quotation->customer->nama ?? $quotation->customer->company ?? 'Customer tidak ditemukan',
                'total' => $quotation->total
            ];

            DB::beginTransaction();

            // Delete quotation details
            QuotationDetail::where('quotation_id', $id)->delete();

            // Delete quotation
            $quotation->delete();

            // Tambahkan log aktivitas untuk delete
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'modul' => 'quotation',
                'data_id' => $quotationData['id'],
                'ip_address' => request()->ip(),
                'detail' => json_encode($quotationData)
            ]);

            DB::commit();

            return redirect()->route('penjualan.quotation.index')
                ->with('success', 'Quotation berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting quotation: ' . $e->getMessage());

            return redirect()->route('penjualan.quotation.index')
                ->with('error', 'Terjadi kesalahan saat menghapus quotation. Error: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,dikirim,disetujui,ditolak,kedaluwarsa',
            'catatan_status' => 'nullable|string',
        ]);

        try {
            $quotation = Quotation::findOrFail($id);
            $oldStatus = $quotation->status;
            $newStatus = $request->status;

            // Validate status workflow
            $isValidStatusChange = $this->isValidStatusChange($oldStatus, $newStatus);

            if (!$isValidStatusChange) {
                if ($oldStatus === $newStatus) {
                    return redirect()->back()
                        ->with('error', 'Status quotation sudah ' . $newStatus . '. Tidak ada perubahan yang dilakukan.');
                } else {
                    return redirect()->back()
                        ->with('error', 'Perubahan status tidak valid. Status yang sudah disetujui atau ditolak tidak dapat dikembalikan ke status sebelumnya.');
                }
            }

            $quotation->status = $newStatus;
            $quotation->catatan_status = $request->catatan_status;
            $quotation->save();

            // Tambahkan log aktivitas untuk perubahan status
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'change_status',
                'modul' => 'quotation',
                'data_id' => $quotation->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $quotation->nomor,
                    'status_lama' => $oldStatus,
                    'status_baru' => $newStatus,
                    'catatan' => $request->catatan_status ?? '-'
                ])
            ]);

            // Log status change
            Log::info("Quotation {$quotation->nomor} status changed from {$oldStatus} to {$newStatus}");

            return redirect()->route('penjualan.quotation.show', $quotation->id)
                ->with('success', "Status quotation berhasil diubah menjadi " . ucfirst($newStatus));
        } catch (\Exception $e) {
            Log::error('Error changing quotation status: ' . $e->getMessage());

            return redirect()->route('penjualan.quotation.show', $id)
                ->with('error', 'Terjadi kesalahan saat mengubah status quotation. Error: ' . $e->getMessage());
        }
    }

    public function getStatusOptions()
    {
        $statuses = [
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'kedaluwarsa' => 'Kedaluwarsa'
        ];

        return response()->json($statuses);
    }

    /**
     * Validates if the status change follows the allowed workflow
     * 
     * Status workflow rules:
     * - A quotation can go from draft to dikirim, disetujui, ditolak, or kedaluwarsa
     * - A quotation can go from dikirim to disetujui, ditolak, or kedaluwarsa
     * - A quotation in disetujui status can only be changed to kedaluwarsa
     * - A quotation in ditolak status can only be changed to kedaluwarsa
     * - A quotation in kedaluwarsa status cannot be changed
     * 
     * @param string $oldStatus Current quotation status
     * @param string $newStatus Requested new status
     * @return bool Whether the status change is valid
     */
    private function isValidStatusChange($oldStatus, $newStatus)
    {
        // No change is invalid - prevent redundant logging of the same status
        if ($oldStatus === $newStatus) {
            return false;
        }

        // Kedaluwarsa can be set from any status
        if ($newStatus === 'kedaluwarsa') {
            return true;
        }

        // Status transition rules
        $allowedTransitions = [
            'draft' => ['dikirim', 'disetujui', 'ditolak', 'kedaluwarsa'],
            'dikirim' => ['disetujui', 'ditolak', 'kedaluwarsa'],
            'disetujui' => ['kedaluwarsa'],
            'ditolak' => ['kedaluwarsa'],
            'kedaluwarsa' => []
        ];

        // Check if the transition is allowed
        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    /**
     * Generate PDF for a quotation
     */
    public function exportPdf($id)
    {
        try {
            // Increase execution time limit for this request
            ini_set('max_execution_time', 120); // Set to 2 minutes

            $quotation = Quotation::with(['customer', 'user', 'details.produk', 'details.satuan'])
                ->findOrFail($id);

            // Load the PDF view
            $pdf = Pdf::loadView('penjualan.quotation.pdf', ['quotation' => $quotation]);

            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');

            // Set lower memory usage options if available
            if (method_exists($pdf, 'setOption')) {
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isPhpEnabled', false);
            }

            // Stream the PDF instead of downloading for better performance
            return $pdf->download('Quotation-' . $quotation->nomor . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
        }
    }

    /**
     * Get quotations for select2 dropdown.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuotationsForSelect(Request $request)
    {
        $search = $request->search;
        $status = $request->status ?? 'disetujui';
        $page = $request->page ?? 1;
        $perPage = 10;

        $query = Quotation::with('customer')
            ->where('status', $status)
            // Filter out quotations that already have associated sales orders
            ->whereDoesntHave('salesOrders');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        $quotations = $query->orderBy('tanggal', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $quotations->items(),
            'current_page' => $quotations->currentPage(),
            'last_page' => $quotations->lastPage(),
            'total' => $quotations->total(),
        ]);
    }
}
