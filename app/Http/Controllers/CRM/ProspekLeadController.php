<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospek;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class ProspekLeadController extends Controller
{
    public function index()
    {
        $prospeks = Prospek::with('user')->orderBy('created_at', 'desc')->get();
        return view('CRM.prospek_and_lead.index', compact('prospeks'));
    }

    public function data(Request $request)
    {
        $query = Prospek::with('user');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_prospek', 'like', "%{$search}%")
                    ->orWhere('perusahaan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply sumber filter
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        // Apply periode filter
        if ($request->filled('periode')) {
            $periode = $request->periode;
            $today = now()->format('Y-m-d');

            switch ($periode) {
                case 'today':
                    $query->whereDate('tanggal_kontak', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('tanggal_kontak', now()->subDay()->format('Y-m-d'));
                    break;
                case 'last_7_days':
                    $query->whereDate('tanggal_kontak', '>=', now()->subDays(7)->format('Y-m-d'));
                    break;
                case 'last_30_days':
                    $query->whereDate('tanggal_kontak', '>=', now()->subDays(30)->format('Y-m-d'));
                    break;
                case 'this_month':
                    $query->whereMonth('tanggal_kontak', now()->month)
                        ->whereYear('tanggal_kontak', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('tanggal_kontak', now()->subMonth()->month)
                        ->whereYear('tanggal_kontak', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('tanggal_kontak', now()->year);
                    break;
            }
        }

        // Apply sorting
        $sortColumn = $request->input('sort_column', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results
        $perPage = $request->input('per_page', 10);
        $prospeks = $query->paginate($perPage);

        return response()->json($prospeks);
    }

    public function create()
    {
        $customers = Customer::all();
        return view('CRM.prospek_and_lead.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_prospek' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|string|in:baru,tertarik,negosiasi,menolak,menjadi_customer',
            'sumber' => 'required|string|in:website,referral,pameran,media_sosial,cold_call,lainnya',
            'nilai_potensi' => 'nullable|numeric|min:0',
            'tanggal_kontak' => 'nullable|date',
            'tanggal_followup' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        // Tambahkan user_id dari user yang sedang login
        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Simpan data prospek
        Prospek::create($data);

        return redirect()->route('crm.prospek.index')
            ->with('success', 'Prospek berhasil ditambahkan');
    }

    public function show($id)
    {
        $prospek = Prospek::with('user')->findOrFail($id);
        return view('CRM.prospek_and_lead.show', compact('prospek'));
    }

    public function edit($id)
    {
        $prospek = Prospek::findOrFail($id);
        $customers = Customer::all();
        return view('CRM.prospek_and_lead.edit', compact('prospek', 'customers'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_prospek' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|string|in:baru,tertarik,negosiasi,menolak,menjadi_customer',
            'sumber' => 'required|string|in:website,referral,pameran,media_sosial,cold_call,lainnya',
            'nilai_potensi' => 'nullable|numeric|min:0',
            'tanggal_kontak' => 'nullable|date',
            'tanggal_followup' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        // Update data prospek
        $prospek = Prospek::findOrFail($id);
        $prospek->update($request->all());

        return redirect()->route('crm.prospek.index')
            ->with('success', 'Prospek berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            // Delete data prospek
            $prospek = Prospek::findOrFail($id);
            $prospek->delete();

            // If it's an AJAX request, return JSON response
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prospek berhasil dihapus'
                ]);
            }

            // Regular form submission response with redirect
            return redirect()->route('crm.prospek.index')
                ->with('success', 'Prospek berhasil dihapus');
        } catch (\Exception $e) {
            // If it's an AJAX request, return JSON error response
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus prospek: ' . $e->getMessage()
                ], 500);
            }

            // Regular form submission error response
            return back()->with('error', 'Gagal menghapus prospek: ' . $e->getMessage());
        }
    }
}
