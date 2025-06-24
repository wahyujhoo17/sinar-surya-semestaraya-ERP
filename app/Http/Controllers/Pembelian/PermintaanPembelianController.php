<?php

namespace App\Http\Controllers\pembelian;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\NotificationService;

class PermintaanPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $dateFilter = $request->query('date_filter'); // today, this_week, this_month, range
        $dateStart = $request->query('date_start');
        $dateEnd = $request->query('date_end');
        $validStatuses = ['draft', 'diajukan', 'disetujui', 'ditolak', 'selesai'];

        $query = PurchaseRequest::with(['user', 'department'])
            ->orderBy('created_at', 'desc');

        // Filter by status if a valid status is provided
        if ($status && in_array($status, $validStatuses)) {
            $query->where('status', $status);
        }

        // Filter by search (nomor, user name)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%$search%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%");
                    });
            });
        }

        // Filter by date
        if ($dateFilter === 'today') {
            $query->whereDate('tanggal', now()->toDateString());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($dateFilter === 'this_month') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        } elseif ($dateFilter === 'range' && $dateStart && $dateEnd) {
            $query->whereBetween('tanggal', [$dateStart, $dateEnd]);
        }

        $permintaanPembelian = $query->paginate(15)->withQueryString();
        $currentStatus = $status;

        // Jika AJAX (fetch dari Alpine), return partial table
        if ($request->ajax()) {
            $tableHtml = view('pembelian.permintaan_pembelian._table', compact('permintaanPembelian'))->render();
            $paginationHtml = $permintaanPembelian->links('vendor.pagination.tailwind-custom')->render();
            return response()->json([
                'table_html' => $tableHtml,
                'pagination_html' => $paginationHtml,
                'success' => true,
            ]);
        }

        return view('pembelian.permintaan_pembelian.index', compact('permintaanPembelian', 'currentStatus', 'validStatuses', 'search', 'dateFilter', 'dateStart', 'dateEnd'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $produks = Produk::where('is_active', true)->get();
        $satuans = Satuan::all();

        // Generate nomor permintaan pembelian
        $today = now()->format('Ymd');
        $lastRequest = PurchaseRequest::where('nomor', 'like', "PR-{$today}%")->orderBy('nomor', 'desc')->first();

        $sequence = '001';
        if ($lastRequest) {
            $lastSequence = (int) substr($lastRequest->nomor, -3);
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        $nomorPR = "PR-{$today}-{$sequence}";

        return view('pembelian.permintaan_pembelian.create', compact('departments', 'produks', 'satuans', 'nomorPR'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'nomor' => ['required', 'string', Rule::unique('purchase_request', 'nomor')],
            'tanggal' => 'required|date',
            'department_id' => 'required|exists:department,id',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.ukuran' => 'nullable|string',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga_estimasi' => 'nullable|numeric|min:0',
        ];

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Create Purchase Request
            $purchaseRequest = PurchaseRequest::create([
                'nomor' => $validatedData['nomor'],
                'tanggal' => $validatedData['tanggal'],
                'user_id' => Auth::id(),
                'department_id' => $validatedData['department_id'],
                'catatan' => $validatedData['catatan'] ?? null,
                'status' => 'draft',
            ]);

            // Create Purchase Request Details
            foreach ($validatedData['items'] as $item) {
                $produk = Produk::find($item['produk_id']);
                $namaItem = $produk ? ($produk->nama . ($item['ukuran'] ? ' (' . $item['ukuran'] . ')' : '')) : 'Nama Produk Tidak Ditemukan';

                PurchaseRequestDetail::create([
                    'pr_id' => $purchaseRequest->id,
                    'produk_id' => $item['produk_id'],
                    'nama_item' => $namaItem,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'quantity' => $item['quantity'],
                    'satuan_id' => $item['satuan_id'],
                    'harga_estimasi' => $item['harga_estimasi'] ?? 0,
                ]);
            }

            DB::commit();
            // Log aktivitas create
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'create',
                'modul' => 'permintaan_pembelian',
                'data_id' => $purchaseRequest->id,
                'ip_address' => $request->ip(),
                'detail' => 'Buat permintaan pembelian: ' . $purchaseRequest->nomor,
            ]);
            return redirect()->route('pembelian.permintaan-pembelian.index')
                ->with('success', 'Permintaan pembelian berhasil  disimpan sebagai draft.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permintaanPembelian = PurchaseRequest::with(['user', 'department', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        return view('pembelian.permintaan_pembelian.show', compact('permintaanPembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permintaanPembelian = PurchaseRequest::with(['user', 'department', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Hanya draft yang bisa diedit
        if ($permintaanPembelian->status !== 'draft') {
            return redirect()->route('pembelian.permintaan-pembelian.show', $id)
                ->with('error', 'Permintaan pembelian yang sudah diajukan tidak dapat diedit.');
        }

        $departments = Department::where('is_active', true)->get();
        $produks = Produk::where('is_active', true)->get();
        $satuans = Satuan::all();

        return view('pembelian.permintaan_pembelian.edit', compact('permintaanPembelian', 'departments', 'produks', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request data - remove nama_item validation
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'department_id' => 'required|exists:department,id',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|sometimes|exists:purchase_request_detail,id', // Use sometimes for new items
            'items.*.produk_id' => 'required|exists:produk,id',
            // 'items.*.nama_item' => 'required|string', // REMOVE THIS VALIDATION
            'items.*.ukuran' => 'nullable|string', // Add validation for ukuran if needed
            'items.*.deskripsi' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga_estimasi' => 'nullable|numeric|min:0',
        ]);

        $purchaseRequest = PurchaseRequest::findOrFail($id);

        // Hanya draft yang bisa diupdate
        if ($purchaseRequest->status !== 'draft') {
            return redirect()->route('pembelian.permintaan-pembelian.show', $id)
                ->with('error', 'Permintaan pembelian yang sudah diajukan tidak dapat diedit.');
        }

        DB::beginTransaction();
        try {
            // Update Purchase Request header
            $purchaseRequest->update([
                'tanggal' => $validatedData['tanggal'],
                'department_id' => $validatedData['department_id'],
                'catatan' => $validatedData['catatan'] ?? null,
                // Status remains 'draft' after editing a draft
            ]);

            $keepDetailIds = [];

            // Update or create details
            foreach ($validatedData['items'] as $item) {
                // --- Reconstruct nama_item ---
                $produk = Produk::find($item['produk_id']);
                $namaItem = $produk ? $produk->nama : 'Produk Tidak Ditemukan';
                if ($produk && !empty($item['ukuran'])) {
                    $namaItem .= ' (' . $item['ukuran'] . ')';
                }
                // --- End Reconstruct ---

                $detailData = [
                    'produk_id' => $item['produk_id'],
                    'nama_item' => $namaItem, // Use reconstructed name
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'quantity' => $item['quantity'],
                    'satuan_id' => $item['satuan_id'],
                    'harga_estimasi' => $item['harga_estimasi'] ?? 0,
                    // 'ukuran' => $item['ukuran'] ?? null, // Store ukuran if needed
                ];

                if (isset($item['id']) && $item['id']) {
                    // Update existing detail
                    $detail = PurchaseRequestDetail::where('id', $item['id'])->where('pr_id', $id)->first();
                    if ($detail) {
                        $detail->update($detailData);
                        $keepDetailIds[] = $detail->id;
                    }
                    // If detail not found or doesn't belong, it might indicate an issue,
                    // but we'll proceed assuming valid IDs are passed for existing items.
                } else {
                    // Create new detail
                    $newDetail = $purchaseRequest->details()->create($detailData);
                    $keepDetailIds[] = $newDetail->id;
                }
            }

            // Delete details that were removed from the form
            PurchaseRequestDetail::where('pr_id', $id)
                ->whereNotIn('id', $keepDetailIds)
                ->delete();

            DB::commit();
            // Log aktivitas update
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'update',
                'modul' => 'permintaan_pembelian',
                'data_id' => $purchaseRequest->id,
                'ip_address' => $request->ip(),
                'detail' => 'Update permintaan pembelian: ' . $purchaseRequest->nomor,
            ]);
            // Redirect to show page after successful update
            return redirect()->route('pembelian.permintaan-pembelian.show', $id)
                ->with('success', 'Permintaan pembelian berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Change the status of the purchase request.
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,diajukan,disetujui,ditolak,selesai',
        ]);

        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $oldStatus = $purchaseRequest->status;

        // Validasi perubahan status yang diizinkan
        $allowedTransitions = [
            'draft' => ['diajukan'],
            'diajukan' => ['disetujui', 'ditolak'],
            'disetujui' => ['selesai'],
            'ditolak' => ['draft'],
            'selesai' => [],
        ];

        if (!in_array($request->status, $allowedTransitions[$oldStatus])) {
            return back()->with('error', "Perubahan status dari '{$oldStatus}' ke '{$request->status}' tidak diizinkan.");
        }

        try {
            $purchaseRequest->update(['status' => $request->status]);

            // Send notifications based on status change
            $notificationService = new NotificationService();
            $currentUser = Auth::user();

            switch ($request->status) {
                case 'diajukan':
                    $notificationService->notifyPurchaseRequestSubmitted($purchaseRequest, $currentUser);
                    break;
                case 'disetujui':
                    $notificationService->notifyPurchaseRequestApproved($purchaseRequest, $currentUser);
                    break;
                case 'ditolak':
                    $rejectionReason = $request->input('rejection_reason'); // Optional reason
                    $notificationService->notifyPurchaseRequestRejected($purchaseRequest, $currentUser, $rejectionReason);
                    break;
                case 'selesai':
                    $notificationService->notifyPurchaseRequestCompleted($purchaseRequest, $currentUser);
                    break;
            }

            // Log aktivitas perubahan status
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'change_status',
                'modul' => 'permintaan_pembelian',
                'data_id' => $purchaseRequest->id,
                'ip_address' => $request->ip(),
                'detail' => 'Status dari ' . $oldStatus . ' ke ' . $request->status . ' untuk PR: ' . $purchaseRequest->nomor,
            ]);
            return redirect()->route('pembelian.permintaan-pembelian.show', $id)
                ->with('success', "Status permintaan pembelian berhasil diubah menjadi {$request->status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        // Hanya draft yang bisa dihapus
        if ($purchaseRequest->status !== 'draft') {
            return redirect()->route('pembelian.permintaan-pembelian.index')
                ->with('error', 'Hanya permintaan pembelian dengan status draft yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Delete details first
            PurchaseRequestDetail::where('pr_id', $id)->delete();

            // Then delete the main record
            $purchaseRequest->delete();

            DB::commit();
            // Log aktivitas hapus
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'modul' => 'permintaan_pembelian',
                'data_id' => $purchaseRequest->id,
                'ip_address' => request()->ip(),
                'detail' => 'Hapus permintaan pembelian: ' . $purchaseRequest->nomor,
            ]);
            return redirect()->route('pembelian.permintaan-pembelian.index')
                ->with('success', 'Permintaan pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pembelian.permintaan-pembelian.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $permintaanPembelian = PurchaseRequest::with(['details.produk', 'user', 'department'])->findOrFail($id);
        $totalEstimasi = $permintaanPembelian->details->sum(fn($d) => $d->quantity * $d->harga_estimasi);

        $pdf = Pdf::loadView('pembelian.permintaan_pembelian.pdf', compact('permintaanPembelian', 'totalEstimasi'));
        return $pdf->download('Permintaan-Pembelian-' . $permintaanPembelian->nomor . '.pdf');
    }
}
