<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Traits\HasPDFQRCode;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturPembelianController extends Controller
{
    use HasPDFQRCode;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validStatuses = ['draft', 'diproses', 'menunggu_barang_pengganti', 'selesai'];
        $status = $request->input('status', 'semua');
        $search = $request->input('search');
        $supplier_id = $request->input('supplier_id');
        $dateFilter = $request->input('date_filter');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Query dasar dengan relasi
        $query = ReturPembelian::with(['supplier', 'purchaseOrder', 'user']);

        // Filter by status
        if ($status && $status !== 'semua' && in_array($status, $validStatuses)) {
            $query->where('status', $status);
        }

        // Filter pencarian (nomor, supplier, nomor PO)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('purchaseOrder', function ($sq) use ($search) {
                        $sq->where('nomor', 'like', "%{$search}%");
                    });
            });
        }

        // Filter supplier
        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        }

        // Filter tanggal
        if ($dateFilter) {
            // Log untuk debugging
            \Log::info('Date filter applied:', [
                'filter' => $dateFilter,
                'start' => $dateStart ?? 'null',
                'end' => $dateEnd ?? 'null',
            ]);

            if ($dateFilter === 'today') {
                $query->whereDate('tanggal', now()->toDateString());
            } elseif ($dateFilter === 'yesterday') {
                $query->whereDate('tanggal', now()->subDay()->toDateString());
            } elseif ($dateFilter === 'this_week') {
                $query->whereBetween('tanggal', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ]);
            } elseif ($dateFilter === 'last_week') {
                $query->whereBetween('tanggal', [
                    now()->subWeek()->startOfWeek()->toDateString(),
                    now()->subWeek()->endOfWeek()->toDateString()
                ]);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
            } elseif ($dateFilter === 'last_month') {
                $query->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
            } elseif ($dateFilter === 'range' && $dateStart && $dateEnd) {
                // Parse date strings to ensure they're in the correct format
                try {
                    $startDate = Carbon::parse($dateStart)->startOfDay();
                    $endDate = Carbon::parse($dateEnd)->endOfDay();
                    $query->whereBetween('tanggal', [$startDate, $endDate]);

                    \Log::info('Date range parsed:', [
                        'original_start' => $dateStart,
                        'original_end' => $dateEnd,
                        'parsed_start' => $startDate->toDateString(),
                        'parsed_end' => $endDate->toDateString(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error parsing date range:', [
                        'error' => $e->getMessage(),
                        'start' => $dateStart,
                        'end' => $dateEnd,
                    ]);
                }
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Get counts for each status with the same filters (except status filter)
        $baseQuery = ReturPembelian::query();

        // Apply the same filters as the main query (except status filter)
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('purchaseOrder', function ($sq) use ($search) {
                        $sq->where('nomor', 'like', "%{$search}%");
                    });
            });
        }

        // Filter supplier
        if ($supplier_id) {
            $baseQuery->where('supplier_id', $supplier_id);
        }

        // Filter tanggal (same as main query)
        if ($dateFilter) {
            if ($dateFilter === 'today') {
                $baseQuery->whereDate('tanggal', now()->toDateString());
            } elseif ($dateFilter === 'yesterday') {
                $baseQuery->whereDate('tanggal', now()->subDay()->toDateString());
            } elseif ($dateFilter === 'this_week') {
                $baseQuery->whereBetween('tanggal', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ]);
            } elseif ($dateFilter === 'last_week') {
                $baseQuery->whereBetween('tanggal', [
                    now()->subWeek()->startOfWeek()->toDateString(),
                    now()->subWeek()->endOfWeek()->toDateString()
                ]);
            } elseif ($dateFilter === 'this_month') {
                $baseQuery->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
            } elseif ($dateFilter === 'last_month') {
                $baseQuery->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
            } elseif ($dateFilter === 'range' && $dateStart && $dateEnd) {
                // Parse date strings to ensure they're in the correct format
                try {
                    $startDate = Carbon::parse($dateStart)->startOfDay();
                    $endDate = Carbon::parse($dateEnd)->endOfDay();
                    $baseQuery->whereBetween('tanggal', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    // Error already logged in the main query
                }
            }
        }

        $statusCounts = [];
        foreach ($validStatuses as $validStatus) {
            $statusCounts[$validStatus] = (clone $baseQuery)->where('status', $validStatus)->count();
        }
        $statusCounts['semua'] = (clone $baseQuery)->count();

        // Paginate results
        $returPembelian = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::orderBy('nama')->get();

        // AJAX response for table partial
        if ($request->ajax()) {
            $table_html = view('pembelian.retur_pembelian._table', [
                'returPembelian' => $returPembelian
            ])->render();

            $pagination_html = $returPembelian->links('vendor.pagination.tailwind-custom')->toHtml();

            return response()->json([
                'table_html' => $table_html,
                'pagination_html' => $pagination_html,
            ]);
        }

        return view('pembelian.retur_pembelian.index', [
            'returPembelian' => $returPembelian,
            'validStatuses' => $validStatuses,
            'statusCounts' => $statusCounts,
            'suppliers' => $suppliers,
            'currentStatus' => $status,
            'search' => $search ?? '',
            'dateFilter' => $dateFilter ?? '',
            'dateStart' => $dateStart ?? '',
            'dateEnd' => $dateEnd ?? '',
            'supplier_id' => $supplier_id ?? '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Generate nomor retur pembelian
        $today = now()->format('Ymd');
        $lastRetur = ReturPembelian::where('nomor', 'like', "RB-{$today}%")
            ->orderBy('nomor', 'desc')
            ->first();

        $sequence = '001';
        if ($lastRetur) {
            $lastSequence = (int) substr($lastRetur->nomor, -3);
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        $nomorRetur = "RB-{$today}-{$sequence}";

        // Log aktivitas
        $this->logActivity('akses', null, 'Mengakses form tambah retur pembelian');

        return view('pembelian.retur_pembelian.create', compact(
            'suppliers',
            'gudangs',
            'satuans',
            'nomorRetur'
        ));
    }

    /**
     * Get purchase orders for a supplier
     */
    public function getPurchaseOrders(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        if (!$supplierId) {
            return response()->json(['purchaseOrders' => []]);
        }

        // Get only purchase orders that have received goods (have GRN)
        // Status can be 'selesai' (completed) or 'dikirim' (shipped)
        $purchaseOrders = PurchaseOrder::where('supplier_id', $supplierId)
            ->whereIn('status', ['dikirim', 'selesai'])
            ->whereIn('status_penerimaan', ['sebagian', 'diterima'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'purchaseOrders' => $purchaseOrders->map(function ($po) {
                return [
                    'id' => $po->id,
                    'nomor' => $po->nomor,
                    'tanggal' => $po->tanggal,
                    'total' => $po->total
                ];
            })
        ]);
    }

    /**
     * Get purchase order items
     */
    public function getPurchaseOrderItems(Request $request)
    {
        $poId = $request->input('po_id');
        if (!$poId) {
            return response()->json(['items' => []]);
        }

        $poDetails = PurchaseOrderDetail::with(['produk', 'satuan'])
            ->where('po_id', $poId)
            ->where('quantity_diterima', '>', 0)
            ->get();

        return response()->json([
            'items' => $poDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'produk_id' => $detail->produk_id,
                    'nama_item' => $detail->produk ? $detail->produk->nama : $detail->nama_item,
                    'kode_produk' => $detail->produk ? $detail->produk->kode : '',
                    'deskripsi' => $detail->deskripsi,
                    'quantity' => $detail->quantity_diterima,
                    'satuan_id' => $detail->satuan_id,
                    'satuan_nama' => $detail->satuan ? $detail->satuan->nama : '',
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => 'required|string|max:50|unique:retur_pembelian,nomor',
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'gudang_id' => 'required|exists:gudang,id',
            'tipe_retur' => 'required|in:pengembalian_dana,tukar_barang',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.alasan' => 'required|string',
            'items.*.keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create retur pembelian header
            $returPembelian = ReturPembelian::create([
                'nomor' => $validated['nomor'],
                'tanggal' => $validated['tanggal'],
                'purchase_order_id' => $validated['purchase_order_id'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'draft',
                'tipe_retur' => $validated['tipe_retur'],
            ]);

            // Create retur pembelian details
            foreach ($validated['items'] as $item) {
                if (empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue; // Skip items with zero or negative quantity
                }

                ReturPembelianDetail::create([
                    'retur_id' => $returPembelian->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'satuan_id' => $item['satuan_id'],
                    'alasan' => $item['alasan'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            // Log aktivitas
            $this->logActivity('tambah', $returPembelian->id, 'Membuat retur pembelian: ' . $returPembelian->nomor);

            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('success', 'Retur Pembelian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returPembelian = ReturPembelian::with([
            'purchaseOrder',
            'supplier',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Get log aktivitas
        $logAktivitas = LogAktivitas::with('user')
            ->where('modul', 'retur_pembelian')
            ->where('data_id', $returPembelian->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get gudang for modal
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        return view('pembelian.retur_pembelian.show', compact('returPembelian', 'logAktivitas', 'gudangs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $returPembelian = ReturPembelian::with([
            'purchaseOrder',
            'supplier',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Only draft status can be edited
        if ($returPembelian->status !== 'draft') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Hanya retur pembelian dengan status draft yang dapat diubah.');
        }

        $suppliers = Supplier::orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Get purchase orders for this supplier
        $purchaseOrders = PurchaseOrder::where('supplier_id', $returPembelian->supplier_id)
            ->whereIn('status', ['diproses', 'selesai'])
            ->where('status_penerimaan', '!=', 'belum_diterima')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Log aktivitas
        $this->logActivity('akses', $returPembelian->id, 'Mengakses form edit retur pembelian: ' . $returPembelian->nomor);

        return view('pembelian.retur_pembelian.edit', compact(
            'returPembelian',
            'suppliers',
            'gudangs',
            'satuans',
            'purchaseOrders'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $returPembelian = ReturPembelian::findOrFail($id);

        // Only draft status can be edited
        if ($returPembelian->status !== 'draft') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Hanya retur pembelian dengan status draft yang dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'gudang_id' => 'required|exists:gudang,id',
            'tipe_retur' => 'required|in:pengembalian_dana,tukar_barang',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.alasan' => 'required|string',
            'items.*.keterangan' => 'nullable|string',
            'items.*.id' => 'nullable|exists:retur_pembelian_detail,id',
        ]);

        try {
            DB::beginTransaction();

            // Update retur pembelian header
            $returPembelian->update([
                'tanggal' => $validated['tanggal'],
                'purchase_order_id' => $validated['purchase_order_id'],
                'supplier_id' => $validated['supplier_id'],
                'catatan' => $validated['catatan'] ?? null,
                'tipe_retur' => $validated['tipe_retur'],
            ]);

            // Get existing detail IDs
            $existingIds = collect($validated['items'])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete removed details
            $returPembelian->details()
                ->whereNotIn('id', $existingIds)
                ->delete();

            // Update or create details
            foreach ($validated['items'] as $item) {
                if (empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue; // Skip items with zero or negative quantity
                }

                $detailData = [
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'satuan_id' => $item['satuan_id'],
                    'alasan' => $item['alasan'],
                    'keterangan' => $item['keterangan'] ?? null,
                ];

                if (!empty($item['id'])) {
                    // Update existing detail
                    ReturPembelianDetail::where('id', $item['id'])
                        ->where('retur_id', $returPembelian->id)
                        ->update($detailData);
                } else {
                    // Create new detail
                    $detailData['retur_id'] = $returPembelian->id;
                    ReturPembelianDetail::create($detailData);
                }
            }

            DB::commit();

            // Log aktivitas
            $this->logActivity('ubah', $returPembelian->id, 'Mengubah retur pembelian: ' . $returPembelian->nomor);

            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('success', 'Retur Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Process the return - deduct inventory from warehouse
     */
    public function prosesRetur($id)
    {
        $returPembelian = ReturPembelian::with([
            'details.produk',
            'details.satuan',
            'supplier',
            'purchaseOrder'
        ])->findOrFail($id);

        // Can only process draft status
        if ($returPembelian->status !== 'draft') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Retur pembelian ini tidak dapat diproses karena statusnya ' . $returPembelian->status);
        }

        try {
            DB::beginTransaction();

            // Update status
            $returPembelian->status = 'diproses';
            $returPembelian->save();

            // Log aktivitas
            $this->logActivity('proses', $returPembelian->id, 'Memproses retur pembelian: ' . $returPembelian->nomor);

            DB::commit();

            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('success', 'Retur pembelian berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete the return process - update inventory and financial status
     */
    public function selesaikanRetur(Request $request, $id)
    {
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id'
        ]);

        $returPembelian = ReturPembelian::with([
            'details.produk',
            'details.satuan',
            'supplier',
            'purchaseOrder.details'
        ])->findOrFail($id);

        // Can only complete from diproses status
        if ($returPembelian->status !== 'diproses') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Retur pembelian ini tidak dapat diselesaikan karena statusnya ' . $returPembelian->status);
        }

        try {
            DB::beginTransaction();

            // Get PO details for validating quantities and getting prices
            $poDetails = $returPembelian->purchaseOrder->details;

            // Deduct inventory for each product
            foreach ($returPembelian->details as $detail) {
                // Validate that the return quantity doesn't exceed the received quantity in PO
                $matchingPoDetail = $poDetails->where('produk_id', $detail->produk_id)->first();

                if (!$matchingPoDetail) {
                    throw new \Exception("Produk {$detail->produk->nama} tidak ditemukan di Purchase Order.");
                }

                if ($matchingPoDetail->quantity_diterima < $detail->quantity) {
                    throw new \Exception("Kuantitas retur untuk produk {$detail->produk->nama} melebihi kuantitas yang diterima di PO.");
                }

                // Find stock record for this product in the warehouse
                $stok = StokProduk::firstOrCreate(
                    [
                        'produk_id' => $detail->produk_id,
                        'gudang_id' => $request->gudang_id,
                    ],
                    [
                        'jumlah' => 0,
                        'nilai' => 0,
                    ]
                );

                // Record current stock level
                $jumlahSebelum = $stok->jumlah;

                // Validate stock levels
                if ($stok->jumlah < $detail->quantity) {
                    throw new \Exception("Stok {$detail->produk->nama} di gudang tidak mencukupi untuk retur. Tersedia: {$stok->jumlah}, dibutuhkan: {$detail->quantity}.");
                }

                // Deduct stock
                $stok->jumlah -= $detail->quantity;
                $stok->save();

                // Record stock history
                RiwayatStok::create([
                    'stok_id' => $stok->id,
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $request->gudang_id,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => -$detail->quantity,
                    'jumlah_setelah' => $stok->jumlah,
                    'jenis' => 'keluar',
                    'referensi_tipe' => 'retur_pembelian',
                    'referensi_id' => $returPembelian->id,
                    'tanggal' => now(),
                    'keterangan' => "Pengurangan dari retur pembelian #{$returPembelian->nomor}",
                    'user_id' => Auth::id()
                ]);
            }

            // Calculate total value of returned items
            $totalNilaiRetur = 0;
            foreach ($returPembelian->details as $detail) {
                $matchingPoDetail = $poDetails->where('produk_id', $detail->produk_id)->first();
                $totalNilaiRetur += $matchingPoDetail->harga * $detail->quantity;
            }

            // Update PO payment status based on the return type and value
            $po = $returPembelian->purchaseOrder;
            $refundNeeded = false;

            // Handle different behavior based on tipe_retur
            if ($returPembelian->tipe_retur === 'pengembalian_dana') {
                // For cash refund type, update financial status
                if ($po->status_pembayaran == 'lunas') {
                    // If PO was already paid in full, create overpayment for refund
                    $po->kelebihan_bayar = ($po->kelebihan_bayar ?? 0) + $totalNilaiRetur;
                    $po->status_pembayaran = 'kelebihan_bayar';
                    $po->save();
                    $refundNeeded = true;
                } elseif ($po->status_pembayaran == 'belum_lunas') {
                    // If PO wasn't fully paid, reduce the debt
                    $po->sisa_hutang -= $totalNilaiRetur;
                    if ($po->sisa_hutang <= 0) {
                        // If we now have overpayment
                        if ($po->sisa_hutang < 0) {
                            $po->kelebihan_bayar = abs($po->sisa_hutang);
                            $po->sisa_hutang = 0;
                            $po->status_pembayaran = 'kelebihan_bayar';
                            $refundNeeded = true;
                        } else {
                            $po->status_pembayaran = 'lunas';
                        }
                    }
                    $po->save();
                }

                // Update status to completed for pengembalian_dana
                $returPembelian->status = 'selesai';
            } else if ($returPembelian->tipe_retur === 'tukar_barang') {
                // For product exchange type, set to waiting for replacement
                $returPembelian->status = 'menunggu_barang_pengganti';

                // No changes to PO financial status for tukar_barang
            }

            // Save changes to retur_pembelian
            $returPembelian->save();

            // Log aktivitas
            $this->logActivity('selesai', $returPembelian->id, 'Menyelesaikan retur pembelian: ' . $returPembelian->nomor . ' dengan nilai Rp ' . number_format($totalNilaiRetur, 0, ',', '.'));

            DB::commit();

            // If refund is needed, redirect to create refund page with a message
            if ($returPembelian->tipe_retur === 'pengembalian_dana' && $refundNeeded) {
                return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                    ->with('success', 'Retur pembelian berhasil diselesaikan. Kelebihan bayar sebesar Rp ' . number_format($po->kelebihan_bayar, 0, ',', '.') . ' tersedia untuk pengembalian dana.')
                    ->with('refundNeeded', true)
                    ->with('poId', $po->id);
            } elseif ($returPembelian->tipe_retur === 'tukar_barang') {
                return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                    ->with('success', 'Retur pembelian untuk tukar barang telah diproses. Status diubah menjadi menunggu barang pengganti. Silakan koordinasikan dengan supplier untuk penggantian barang.');
            }

            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('success', 'Retur pembelian berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to create a refund for a purchase return
     */
    public function createRefund($id)
    {
        $returPembelian = ReturPembelian::with(['purchaseOrder'])->findOrFail($id);

        // Verify that the return is completed and PO has kelebihan_bayar
        if ($returPembelian->status !== 'selesai') {
            return redirect()->route('pembelian.retur-pembelian.show', $id)
                ->with('error', 'Pengembalian dana hanya dapat dibuat untuk retur pembelian yang sudah selesai.');
        }

        $po = $returPembelian->purchaseOrder;

        if (!$po || $po->status_pembayaran !== 'kelebihan_bayar' || $po->kelebihan_bayar <= 0) {
            return redirect()->route('pembelian.retur-pembelian.show', $id)
                ->with('error', 'Purchase Order terkait tidak memiliki kelebihan pembayaran untuk dikembalikan.');
        }

        // Redirect to create refund form with PO ID
        return redirect()->route('keuangan.pengembalian-dana.create', ['po_id' => $po->id])
            ->with('info', 'Silakan lengkapi form pengembalian dana untuk retur pembelian #' . $returPembelian->nomor);
    }

    /**
     * Show form to record receipt of replacement products
     */
    public function showTerimaBarangPengganti($id)
    {
        $returPembelian = ReturPembelian::with([
            'details.produk',
            'details.satuan',
            'supplier',
            'purchaseOrder'
        ])->findOrFail($id);

        // Only return with status "menunggu_barang_pengganti" can receive replacement
        if ($returPembelian->status !== 'menunggu_barang_pengganti') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Hanya retur dengan status menunggu barang pengganti yang dapat diproses.');
        }

        // Get products from supplier
        $produks = Produk::with('satuan')
            ->whereHas('supplierProduks', function ($query) use ($returPembelian) {
                $query->where('supplier_id', $returPembelian->supplier_id);
            })
            ->orWhereIn('id', $returPembelian->details->pluck('produk_id')->toArray())
            ->orderBy('nama')
            ->get();

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        // Log aktivitas
        $this->logActivity('akses', $returPembelian->id, 'Mengakses form penerimaan barang pengganti untuk retur: ' . $returPembelian->nomor);

        return view('pembelian.retur_pembelian.terima_barang_pengganti', compact(
            'returPembelian',
            'produks',
            'gudangs'
        ));
    }

    /**
     * Process receipt of replacement products
     */
    public function terimaBarangPengganti(Request $request, $id)
    {
        $request->validate([
            'tanggal_penerimaan' => 'required|date',
            'gudang_id' => 'required|exists:gudang,id',
            'no_referensi' => 'nullable|string|max:50',
            'catatan_penerimaan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id'
        ]);

        $returPembelian = ReturPembelian::findOrFail($id);

        // Only return with status "menunggu_barang_pengganti" can receive replacement
        if ($returPembelian->status !== 'menunggu_barang_pengganti') {
            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('error', 'Hanya retur dengan status menunggu barang pengganti yang dapat diproses.');
        }

        DB::beginTransaction();
        try {
            // Process each replacement item
            foreach ($request->items as $item) {
                // Find stock record for this product in the warehouse
                $stok = StokProduk::firstOrCreate(
                    [
                        'produk_id' => $item['produk_id'],
                        'gudang_id' => $request->gudang_id,
                    ],
                    [
                        'jumlah' => 0,
                        'nilai' => 0,
                    ]
                );

                // Record current stock level
                $jumlahSebelum = $stok->jumlah;

                // Add stock - increase inventory
                $stok->jumlah += $item['quantity'];
                $stok->save();

                // Get product name for record
                $produk = Produk::find($item['produk_id']);
                $produkNama = $produk ? $produk->nama : 'Produk #' . $item['produk_id'];

                // Record stock history
                RiwayatStok::create([
                    'stok_id' => $stok->id,
                    'produk_id' => $item['produk_id'],
                    'gudang_id' => $request->gudang_id,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => $item['quantity'],
                    'jumlah_setelah' => $stok->jumlah,
                    'jenis' => 'masuk',
                    'referensi_tipe' => 'retur_pembelian_pengganti',
                    'referensi_id' => $returPembelian->id, // Using numerical ID instead of nomor string
                    'tanggal' => $request->tanggal_penerimaan,
                    'keterangan' => "Penerimaan barang pengganti {$produkNama} untuk retur #{$returPembelian->nomor}",
                    'user_id' => Auth::id()
                ]);
            }

            // Update status to complete
            $returPembelian->status = 'selesai';
            $returPembelian->save();

            // Log aktivitas
            $this->logActivity(
                'terima_barang_pengganti',
                $returPembelian->id,
                'Menerima barang pengganti untuk retur pembelian: ' . $returPembelian->nomor
            );

            DB::commit();

            return redirect()->route('pembelian.retur-pembelian.show', $returPembelian->id)
                ->with('success', 'Penerimaan barang pengganti berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $returPembelian = ReturPembelian::findOrFail($id);

        // Only draft status can be deleted
        if ($returPembelian->status !== 'draft') {
            return redirect()->route('pembelian.retur-pembelian.index')
                ->with('error', 'Hanya retur pembelian dengan status draft yang dapat dihapus.');
        }

        try {
            DB::beginTransaction();

            // Store info for log
            $returInfo = [
                'nomor' => $returPembelian->nomor,
                'supplier' => $returPembelian->supplier ? $returPembelian->supplier->nama : 'Unknown'
            ];

            // Delete details first
            $returPembelian->details()->delete();

            // Delete the retur pembelian
            $returPembelian->delete();

            DB::commit();

            // Log aktivitas
            $this->logActivity('hapus', $id, 'Menghapus retur pembelian: ' . $returInfo['nomor']);

            return redirect()->route('pembelian.retur-pembelian.index')
                ->with('success', 'Retur pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF
     */
    public function exportPdf(string $id)
    {
        // Increase the execution time limit for PDF generation
        ini_set('max_execution_time', 300); // Set to 5 minutes (300 seconds)
        // Increase memory limit for larger PDF generation
        ini_set('memory_limit', '512M');

        $returPembelian = ReturPembelian::with([
            'purchaseOrder',
            'supplier',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Fix the typo in the namespace, it should be Models not Modals
        $company = \App\Models\Company::first();

        // Generate QR codes for signatures
        $qrCodes = $this->generateQRCodes($returPembelian);

        // Get log activity for approval/processing status
        $approvedBy = null;
        $approvedAt = null;
        $isApproved = false;

        // Check for approval in log activities
        $approvalLog = LogAktivitas::where('modul', 'retur_pembelian')
            ->where('data_id', $returPembelian->id)
            ->where('aktivitas', 'LIKE', '%setuju%')
            ->orWhere('aktivitas', 'LIKE', '%proses%')
            ->first();

        if ($approvalLog) {
            $approvedBy = $approvalLog->user;
            $approvedAt = Carbon::parse($approvalLog->created_at);
            $isApproved = true;
        }

        // Cache key based on retur pembelian ID and its updated timestamp to ensure fresh data
        $cacheKey = 'retur_pembelian_pdf_' . $id . '_' . $returPembelian->updated_at->timestamp;
        $cacheDuration = 60; // Cache for 1 hour (60 minutes)

        // Try to get PDF from cache first
        if (\Illuminate\Support\Facades\Cache::has($cacheKey) && config('app.env') !== 'local') {
            $pdf = \Illuminate\Support\Facades\Cache::get($cacheKey);
        } else {
            // Generate PDF if not in cache
            $pdf = Pdf::loadView('pembelian.retur_pembelian.pdf', compact(
                'returPembelian',
                'company',
                'qrCodes',
                'approvedBy',
                'approvedAt',
                'isApproved'
            ));
            // Set paper size and optimize for the PDF
            $pdf->setPaper('a4');

            // Store in cache if not in local environment
            if (config('app.env') !== 'local') {
                \Illuminate\Support\Facades\Cache::put($cacheKey, $pdf, $cacheDuration);
            }
        }

        // Check if we should stream or download the PDF
        $action = request()->query('action', 'download');
        $filename = 'Retur-Pembelian-' . $returPembelian->nomor . '.pdf';

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
     * Helper untuk mencatat log aktivitas user
     */
    private function logActivity($aktivitas, $data_id = null, $detail = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'modul' => 'retur_pembelian',
            'data_id' => $data_id,
            'ip_address' => request()->ip(),
            'detail' => $detail ? (is_array($detail) ? json_encode($detail) : $detail) : null,
        ]);
    }

    /**
     * Generate QR codes for retur pembelian PDF signatures
     */
    private function generateQRCodes($returPembelian)
    {
        // Get log activities for approval
        $approvalLog = LogAktivitas::where('modul', 'retur_pembelian')
            ->where('data_id', $returPembelian->id)
            ->where(function ($query) {
                $query->where('aktivitas', 'LIKE', '%setuju%')
                    ->orWhere('aktivitas', 'LIKE', '%proses%')
                    ->orWhere('aktivitas', 'LIKE', '%approved%');
            })
            ->first();

        $processedBy = null;
        $processedAt = null;

        if ($approvalLog) {
            $processedBy = $approvalLog->user;
            $processedAt = Carbon::parse($approvalLog->created_at);
        }

        return $this->generatePDFQRCodes(
            'retur_pembelian',
            $returPembelian->id,
            $returPembelian->nomor,
            $returPembelian->user, // Created by
            $processedBy, // Processed by
            $processedAt, // Processed at
            [
                'supplier' => $returPembelian->supplier->nama,
                'total_items' => $returPembelian->details->count(),
                'status' => $returPembelian->status,
                'purchase_order' => $returPembelian->purchaseOrder->nomor ?? null
            ]
        );
    }
}
