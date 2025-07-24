<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['customer', 'salesOrder', 'transaksi'])
            ->aktif()
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBudget = $projects->sum('budget');
        $totalAlokasi = $projects->sum('saldo');
        $sisaBudget = $totalBudget - $totalAlokasi;

        return response()->json([
            'projects' => $projects,
            'summary' => [
                'total_budget' => $totalBudget,
                'total_alokasi' => $totalAlokasi,
                'sisa_budget' => $sisaBudget,
                'total_projects' => $projects->count()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'customer_id' => 'nullable|exists:customer,id',
            'sales_order_id' => 'nullable|exists:sales_order,id',
            'pic_internal' => 'nullable|string|max:255',
            'pic_customer' => 'nullable|string|max:255',
            'status' => 'required|in:draft,aktif,selesai,ditunda',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate kode project
            $lastProject = Project::whereYear('created_at', date('Y'))
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastProject ?
                intval(substr($lastProject->kode, -3)) + 1 : 1;

            $kode = 'PROJ-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $project = Project::create([
                'kode' => $kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'budget' => $request->budget,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'customer_id' => $request->customer_id,
                'sales_order_id' => $request->sales_order_id,
                'pic_internal' => $request->pic_internal,
                'pic_customer' => $request->pic_customer,
                'status' => $request->status,
                'is_aktif' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dibuat',
                'data' => $project->load(['customer', 'salesOrder'])
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $project = Project::with(['customer', 'salesOrder', 'transaksi.user', 'transaksi.kas', 'transaksi.rekeningBank'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'customer_id' => 'nullable|exists:customer,id',
            'sales_order_id' => 'nullable|exists:sales_order,id',
            'pic_internal' => 'nullable|string|max:255',
            'pic_customer' => 'nullable|string|max:255',
            'status' => 'required|in:draft,aktif,selesai,ditunda',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $project->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'budget' => $request->budget,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'customer_id' => $request->customer_id,
                'sales_order_id' => $request->sales_order_id,
                'pic_internal' => $request->pic_internal,
                'pic_customer' => $request->pic_customer,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diupdate',
                'data' => $project->load(['customer', 'salesOrder'])
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Cek apakah masih ada transaksi
            if ($project->transaksi()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project tidak dapat dihapus karena masih memiliki transaksi'
                ], 422);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCustomers()
    {
        $customers = Customer::select('id', 'nama', 'kode')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    public function getSalesOrders($customerId = null)
    {
        $query = SalesOrder::select('id', 'nomor', 'tanggal', 'customer_id', 'total')
            ->with('customer:id,nama');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $salesOrders = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $salesOrders
        ]);
    }
}
