<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\ProductBundle;
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
        $prefix = get_document_prefix('quotation') . '-';
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
                    'pagination_html' => $quotations->links('vendor.pagination.tailwind-custom')->toHtml(),
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
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan')) {
            // Allow access to all customers
            $customers = Customer::orderBy('nama', 'asc')->get();
        } else {
            // Only show customers assigned to the sales user
            $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        }
        $products = Produk::orderBy('nama', 'asc')->get();
        $bundles = ProductBundle::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
        $nomor = $this->generateNewQuotationNumber();
        $tanggal = now()->format('Y-m-d');
        $tanggal_berlaku = now()->addDays(30)->format('Y-m-d');
        $quotation_terms = setting('quotation_terms', "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima.");
        $statuses = [
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'kedaluwarsa' => 'Kedaluwarsa'
        ];

        return view('penjualan.quotation.create', compact('customers', 'products', 'bundles', 'satuans', 'nomor', 'tanggal', 'tanggal_berlaku', 'quotation_terms', 'statuses'));
    }

    public function store(Request $request)
    {
        Log::info('Quotation store request data:', $request->all());

        // Preprocess items untuk bundle validation
        $items = collect($request->items ?? [])->map(function ($item, $index) {
            $isBundle = isset($item['is_bundle']) && $item['is_bundle'];
            $isBundleItem = isset($item['is_bundle_item']) && $item['is_bundle_item'];
            $hasProdukId = !empty($item['produk_id']);
            $hasBundleId = !empty($item['bundle_id']);

            Log::info("Item {$index} flags:", [
                'is_bundle' => $isBundle,
                'is_bundle_item' => $isBundleItem,
                'has_produk_id' => $hasProdukId,
                'has_bundle_id' => $hasBundleId
            ]);

            // Fix conflicting flags - an item cannot be both bundle header and bundle item
            if ($isBundle && $isBundleItem) {
                if ($hasProdukId && $hasBundleId) {
                    // Has both produk_id and bundle_id, prioritize as bundle item
                    unset($item['is_bundle']);
                    $item['is_bundle_item'] = true;
                    $isBundle = false;
                } elseif ($hasBundleId && !$hasProdukId) {
                    // Has bundle_id but no produk_id, prioritize as bundle header
                    unset($item['is_bundle_item']);
                    $item['is_bundle'] = true;
                    $isBundleItem = false;
                }
            }

            // Apply final logic
            if ($hasBundleId) {
                if (!$hasProdukId) {
                    // Has bundle_id but no produk_id, treat as bundle header
                    unset($item['is_bundle_item']);
                    $item['is_bundle'] = true;
                } else {
                    // Has both produk_id and bundle_id, treat as bundle child item
                    unset($item['is_bundle']);
                    $item['is_bundle_item'] = true;
                }
            } else {
                // No bundle_id, treat as regular item
                unset($item['is_bundle']);
                unset($item['is_bundle_item']);
                unset($item['bundle_id']);
            }

            return $item;
        })->toArray();

        // Build dynamic validation rules
        $validationRules = [
            'nomor' => 'required|string|unique:quotation,nomor',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'tanggal_berlaku' => 'required|date',
            'status' => 'required|string|in:draft,dikirim,disetujui,ditolak,kedaluwarsa',
            'catatan' => 'nullable|string',
            'syarat_pembayaran' => 'nullable|string',
            'items' => 'required|array|min:1',
            'diskon_global_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
            'ongkos_kirim' => 'nullable|numeric|min:0',
        ];

        // Add validation for each item
        foreach ($items as $index => $item) {
            $isBundle = isset($item['is_bundle']) && $item['is_bundle'];
            $isBundleItem = isset($item['is_bundle_item']) && $item['is_bundle_item'];
            $hasProdukId = !empty($item['produk_id']);
            $hasBundleId = !empty($item['bundle_id']);

            // Fixed logic validation
            if ($hasBundleId && !$hasProdukId) {
                // Has bundle_id but no produk_id, treat as bundle header
                unset($items[$index]['is_bundle_item']);
                $isBundleItem = false;
            } else if ($hasProdukId && $hasBundleId) {
                // Has both produk_id and bundle_id, treat as bundle child item
                unset($items[$index]['is_bundle']);
                $isBundle = false;
            } else {
                // Invalid combination or regular item wrongly flagged, remove all bundle flags
                unset($items[$index]['is_bundle']);
                unset($items[$index]['is_bundle_item']);
                unset($items[$index]['bundle_id']);
                $isBundle = false;
                $isBundleItem = false;
            }

            if ($isBundle) {
                // Bundle header validation
                $validationRules["items.{$index}.is_bundle"] = 'required|boolean';
                $validationRules["items.{$index}.bundle_id"] = 'required|exists:product_bundles,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen"] = 'nullable|numeric|min:0|max:100';
            } elseif ($isBundleItem) {
                // Bundle item validation
                $validationRules["items.{$index}.is_bundle_item"] = 'required|boolean';
                $validationRules["items.{$index}.produk_id"] = 'required|exists:produk,id';
                $validationRules["items.{$index}.bundle_id"] = 'required|exists:product_bundles,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.satuan_id"] = 'required|exists:satuan,id';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen"] = 'nullable|numeric|min:0|max:100';
            } else {
                // Regular item validation
                $validationRules["items.{$index}.produk_id"] = 'required|exists:produk,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.satuan_id"] = 'required|exists:satuan,id';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen"] = 'nullable|numeric|min:0|max:100';
            }
        }

        // Update request with processed items
        $request->merge(['items' => $items]);
        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            $subtotal = 0;

            // Calculate subtotal (skip bundle headers, only count bundle child items and regular items)
            foreach ($items as $item) {
                // Skip bundle headers as they are only for display/organization
                if (isset($item['is_bundle']) && $item['is_bundle']) {
                    continue;
                }

                $productTotal = $item['harga'] * $item['kuantitas'];
                $discountValue = 0;

                $diskonPersen = $item['diskon_persen_item'] ?? $item['diskon_persen'] ?? 0;
                if ($diskonPersen > 0) {
                    $discountValue = ($diskonPersen / 100) * $productTotal;
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
            $ongkosKirim = $request->ongkos_kirim ?? 0;
            $total = $afterDiscount + $ppnValue + $ongkosKirim;

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
            $quotation->ongkos_kirim = $ongkosKirim;
            $quotation->total = $total;
            $quotation->status = $request->status;
            $quotation->tanggal_berlaku = $request->tanggal_berlaku;
            $quotation->catatan = $request->catatan;
            $quotation->syarat_ketentuan = $request->syarat_pembayaran;
            $quotation->save();

            // Create Quotation Details
            foreach ($items as $item) {
                // Skip bundle headers as they are only for display/organization
                if (isset($item['is_bundle']) && $item['is_bundle']) {
                    continue;
                }

                $productTotal = $item['harga'] * $item['kuantitas'];
                $diskonPersenItem = $item['diskon_persen_item'] ?? $item['diskon_persen'] ?? 0;
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

                // Add bundle information if this is a bundle item
                if (isset($item['is_bundle_item']) && $item['is_bundle_item']) {
                    $quotationDetail->item_type = 'bundle_item';
                    $quotationDetail->bundle_id = $item['bundle_id'] ?? null;
                    $quotationDetail->is_bundle_item = true;
                } else {
                    $quotationDetail->item_type = 'produk';
                }

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
        $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan', 'details.bundle', 'logAktivitas.user'])->findOrFail($id);

        return view('penjualan.quotation.show', compact('quotation'));
    }

    public function edit($id)
    {
        $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan', 'details.bundle'])->findOrFail($id);
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan')) {
            // Allow access to all customers
            $customers = Customer::orderBy('nama', 'asc')->get();
        } else {
            // Only show customers assigned to the sales user
            $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        }
        $products = Produk::orderBy('nama', 'asc')->get();
        $bundles = ProductBundle::with(['items.produk.satuan'])->orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
        $quotation_terms = setting('quotation_terms', "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima.");
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

        return view('penjualan.quotation.edit', compact('quotation', 'customers', 'products', 'bundles', 'satuans', 'quotation_terms', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        // Check status, only draft can be updated
        if ($quotation->status !== 'draft') {
            return redirect()->route('penjualan.quotation.index')
                ->with('error', 'Hanya quotation dengan status Draft yang dapat diupdate');
        }

        Log::info('Quotation update request data:', $request->all());

        // Preprocess items untuk bundle validation
        $items = collect($request->items ?? [])->map(function ($item, $index) {
            $isBundle = isset($item['is_bundle']) && $item['is_bundle'];
            $isBundleItem = isset($item['is_bundle_item']) && $item['is_bundle_item'];
            $hasProdukId = !empty($item['produk_id']);
            $hasBundleId = !empty($item['bundle_id']);

            Log::info("Item {$index} flags:", [
                'is_bundle' => $isBundle,
                'is_bundle_item' => $isBundleItem,
                'has_produk_id' => $hasProdukId,
                'has_bundle_id' => $hasBundleId
            ]);

            // Fix conflicting flags - an item cannot be both bundle header and bundle item
            if ($isBundle && $isBundleItem) {
                if ($hasProdukId && $hasBundleId) {
                    // Has both produk_id and bundle_id, prioritize as bundle item
                    unset($item['is_bundle']);
                    $item['is_bundle_item'] = true;
                    $isBundle = false;
                } elseif ($hasBundleId && !$hasProdukId) {
                    // Has bundle_id but no produk_id, prioritize as bundle header
                    unset($item['is_bundle_item']);
                    $item['is_bundle'] = true;
                    $isBundleItem = false;
                }
            }

            // Apply final logic
            if ($hasBundleId) {
                if (!$hasProdukId) {
                    // Has bundle_id but no produk_id, treat as bundle header
                    unset($item['is_bundle_item']);
                    $item['is_bundle'] = true;
                } else {
                    // Has both produk_id and bundle_id, treat as bundle child item
                    unset($item['is_bundle']);
                    $item['is_bundle_item'] = true;
                }
            } else {
                // No bundle_id, treat as regular item
                unset($item['is_bundle']);
                unset($item['is_bundle_item']);
                unset($item['bundle_id']);
            }

            return $item;
        })->toArray();

        // Build dynamic validation rules
        $validationRules = [
            'nomor' => 'required|string|unique:quotation,nomor,' . $id . ',id',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'tanggal_berlaku' => 'required|date',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
            'ongkos_kirim' => 'nullable|numeric|min:0',
        ];

        // Add validation for each item
        foreach ($items as $index => $item) {
            $isBundle = isset($item['is_bundle']) && $item['is_bundle'];
            $isBundleItem = isset($item['is_bundle_item']) && $item['is_bundle_item'];
            $hasProdukId = !empty($item['produk_id']);
            $hasBundleId = !empty($item['bundle_id']);

            // Fixed logic validation
            if ($hasBundleId && !$hasProdukId) {
                // Has bundle_id but no produk_id, treat as bundle header
                unset($items[$index]['is_bundle_item']);
                $isBundleItem = false;
            } else if ($hasProdukId && $hasBundleId) {
                // Has both produk_id and bundle_id, treat as bundle child item
                unset($items[$index]['is_bundle']);
                $isBundle = false;
            } else {
                // Invalid combination or regular item wrongly flagged, remove all bundle flags
                unset($items[$index]['is_bundle']);
                unset($items[$index]['is_bundle_item']);
                unset($items[$index]['bundle_id']);
                $isBundle = false;
                $isBundleItem = false;
            }

            if ($isBundle) {
                // Bundle header validation
                $validationRules["items.{$index}.is_bundle"] = 'required|boolean';
                $validationRules["items.{$index}.bundle_id"] = 'required|exists:product_bundles,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen_item"] = 'nullable|numeric|min:0|max:100';
            } elseif ($isBundleItem) {
                // Bundle item validation
                $validationRules["items.{$index}.is_bundle_item"] = 'required|boolean';
                $validationRules["items.{$index}.produk_id"] = 'required|exists:produk,id';
                $validationRules["items.{$index}.bundle_id"] = 'required|exists:product_bundles,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.satuan_id"] = 'required|exists:satuan,id';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen_item"] = 'nullable|numeric|min:0|max:100';
            } else {
                // Regular item validation
                $validationRules["items.{$index}.produk_id"] = 'required|exists:produk,id';
                $validationRules["items.{$index}.kuantitas"] = 'required|numeric|min:0.01';
                $validationRules["items.{$index}.satuan_id"] = 'required|exists:satuan,id';
                $validationRules["items.{$index}.harga"] = 'required|numeric|min:0';
                $validationRules["items.{$index}.deskripsi"] = 'nullable|string';
                $validationRules["items.{$index}.diskon_persen_item"] = 'nullable|numeric|min:0|max:100';
            }
        }

        // Update request with processed items
        $request->merge(['items' => $items]);
        $request->validate($validationRules);

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

            // Calculate subtotal (skip bundle headers, only count bundle child items and regular items)
            foreach ($items as $item) {
                // Skip bundle headers as they are only for display/organization
                if (isset($item['is_bundle']) && $item['is_bundle']) {
                    continue;
                }

                $productTotal = $item['harga'] * $item['kuantitas'];
                $discountValue = 0;

                if (isset($item['diskon_persen_item']) && $item['diskon_persen_item'] > 0) {
                    $discountValue = ($item['diskon_persen_item'] / 100) * $productTotal;
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
            $ongkosKirim = $request->ongkos_kirim ?? 0;
            $total = $afterDiscount + $ppnValue + $ongkosKirim;

            // Update Quotation
            $quotation->nomor = $request->nomor;
            $quotation->tanggal = $request->tanggal;
            $quotation->customer_id = $request->customer_id;
            $quotation->subtotal = $subtotal;
            $quotation->diskon_persen = $diskonPersen;
            $quotation->diskon_nominal = $diskonNominal;
            $quotation->ppn = $ppn;
            $quotation->ongkos_kirim = $ongkosKirim;
            $quotation->total = $total;
            $quotation->tanggal_berlaku = $request->tanggal_berlaku;
            $quotation->catatan = $request->catatan;
            $quotation->syarat_ketentuan = $request->syarat_ketentuan;
            $quotation->save();

            // Delete existing details and create new ones
            QuotationDetail::where('quotation_id', $quotation->id)->delete();

            // Create Quotation Details
            foreach ($items as $item) {
                // Skip bundle headers as they are only for display/organization
                if (isset($item['is_bundle']) && $item['is_bundle']) {
                    continue;
                }

                $productTotal = $item['harga'] * $item['kuantitas'];
                $diskonPersenItem = $item['diskon_persen_item'] ?? $item['diskon_persen'] ?? 0;
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

                // Add bundle information if this is a bundle item
                if (isset($item['is_bundle_item']) && $item['is_bundle_item']) {
                    $quotationDetail->item_type = 'bundle_item';
                    $quotationDetail->bundle_id = $item['bundle_id'] ?? null;
                    $quotationDetail->is_bundle_item = true;
                } else {
                    $quotationDetail->item_type = 'produk';
                }

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
     * Get bundle data for AJAX request
     */
    public function getBundleData($bundleId)
    {
        try {
            $bundle = ProductBundle::with([
                'items.produk.satuan'
            ])->findOrFail($bundleId);

            $bundleItems = $bundle->items->map(function ($item) {
                return [
                    'produk_id' => $item->produk_id,
                    'produk_nama' => $item->produk->nama ?? '',
                    'produk_kode' => $item->produk->kode ?? '',
                    'quantity' => $item->quantity,
                    'satuan_id' => $item->produk->satuan_id ?? null,
                    'satuan_nama' => $item->produk->satuan->nama ?? '',
                    'harga_satuan' => $item->harga_satuan ?? 0,
                ];
            });

            return response()->json([
                'success' => true,
                'bundle' => [
                    'id' => $bundle->id,
                    'nama' => $bundle->nama,
                    'kode' => $bundle->kode,
                    'deskripsi' => $bundle->deskripsi,
                    'harga_bundle' => $bundle->harga_bundle ?? 0,
                    'diskon_persen' => $bundle->diskon_persen ?? 0, // Add discount percentage
                ],
                'items' => $bundleItems
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting bundle data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bundle not found'
            ], 404);
        }
    }

    /**
     * Generate PDF for a quotation
     */
    public function exportPdf($id, $template = 'default')
    {
        try {
            // Increase memory limit and execution time for PDF generation
            ini_set('memory_limit', '256M');
            ini_set('max_execution_time', 60);

            // Log PDF generation start for debugging
            Log::info("PDF generation started for quotation ID: {$id}, template: {$template}");

            $quotation = Quotation::with(['customer', 'user', 'details.produk', 'details.satuan', 'details.bundle'])
                ->findOrFail($id);

            // Define available templates and their configurations
            $templates = [
                'default' => [
                    'view' => 'penjualan.quotation.pdf',
                    'company_name' => 'PT. SINAR SURYA SEMESTARAYA',
                    'filename_prefix' => 'Quotation-SS'
                ],
                'indo-atsaka' => [
                    'view' => 'penjualan.quotation.pdf-indo-atsaka',
                    'company_name' => 'PT INDO ATSAKA INDUSTRI',
                    'filename_prefix' => 'Quotation-IAI'
                ],
                'hidayah-cahaya' => [
                    'view' => 'penjualan.quotation.pdf-hidayah-cahaya',
                    'company_name' => 'PT HIDAYAH CAHAYA BERKAH',
                    'filename_prefix' => 'Quotation-HCB'
                ]
            ];

            // Validate template
            if (!array_key_exists($template, $templates)) {
                $template = 'default';
            }

            $templateConfig = $templates[$template];

            // Load the PDF view with optimized settings
            $pdf = Pdf::loadView($templateConfig['view'], [
                'quotation' => $quotation,
                'template_config' => $templateConfig
            ]);

            // Set paper size and orientation with optimization
            $pdf->setPaper('a4', 'portrait');

            // Set optimized options for better performance and rendering
            $pdf->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 96,
                'debugKeepTemp' => false,
                'chroot' => public_path(),
                'logOutputFile' => storage_path('logs/dompdf.log'),
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
                'enable_css_float' => true,
                'enable_remote' => true,
                'fontHeightRatio' => 1.1,
            ]);

            // Generate filename
            $filename = $templateConfig['filename_prefix'] . '-' . $quotation->nomor . '.pdf';

            // Stream PDF with headers to open in new tab
            return response($pdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->header('Cache-Control', 'private, max-age=0, must-revalidate')
                ->header('Pragma', 'public');
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
