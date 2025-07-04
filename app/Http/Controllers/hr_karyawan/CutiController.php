<?php

namespace App\Http\Controllers\hr_karyawan;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CutiExport;

class CutiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cuti.view')->only(['index', 'show']);
        $this->middleware('permission:cuti.create')->only(['create', 'store']);
        $this->middleware('permission:cuti.edit')->only(['edit', 'update']);
        $this->middleware('permission:cuti.delete')->only(['destroy']);
        $this->middleware('permission:cuti.approve')->only(['updateStatus']);
        $this->middleware('permission:cuti.view')->only(['exportExcel', 'exportPdf', 'getDepartments', 'getEmployeesByDepartment']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Cuti::with(['karyawan.department', 'karyawan.jabatan', 'approver']);

            // Filter berdasarkan pencarian
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('karyawan', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%');
                    })
                        ->orWhere('jenis_cuti', 'like', '%' . $search . '%')
                        ->orWhere('keterangan', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                });
            }

            // Filter berdasarkan status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan jenis cuti
            if ($request->has('jenis_cuti') && !empty($request->jenis_cuti)) {
                $query->where('jenis_cuti', $request->jenis_cuti);
            }

            // Filter berdasarkan karyawan
            if ($request->has('karyawan_id') && !empty($request->karyawan_id)) {
                $query->where('karyawan_id', $request->karyawan_id);
            }

            // Filter berdasarkan departemen
            if ($request->has('department_id') && !empty($request->department_id)) {
                $query->whereHas('karyawan', function ($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            // Filter berdasarkan tanggal
            if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
                $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
            }

            if ($request->has('tanggal_akhir') && !empty($request->tanggal_akhir)) {
                $query->whereDate('tanggal_selesai', '<=', $request->tanggal_akhir);
            }

            // Pengurutan
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->input('per_page', 15);
            $cuti = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $cuti->items(),
                'pagination' => [
                    'current_page' => $cuti->currentPage(),
                    'last_page' => $cuti->lastPage(),
                    'per_page' => $cuti->perPage(),
                    'total' => $cuti->total(),
                    'from' => $cuti->firstItem(),
                    'to' => $cuti->lastItem(),
                ]
            ]);
        }

        // Data untuk filter dropdown
        $departments = Department::orderBy('nama')->get();
        $karyawan = Karyawan::with('department')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        // Statistik cuti
        $stats = [
            'total_pengajuan' => Cuti::count(),
            'pending' => Cuti::where('status', 'diajukan')->count(),
            'disetujui' => Cuti::where('status', 'disetujui')->count(),
            'ditolak' => Cuti::where('status', 'ditolak')->count(),
            'sedang_cuti' => Cuti::where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->count()
        ];

        return view('hr_karyawan.cuti.index', compact('departments', 'karyawan', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_cuti' => 'required|in:tahunan,sakit,melahirkan,penting,lainnya',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:1000'
        ]);

        try {
            // Hitung jumlah hari
            $tanggalMulai = Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

            // Cek overlapping cuti
            $overlapping = Cuti::where('karyawan_id', $request->karyawan_id)
                ->where('status', '!=', 'ditolak')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                        ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                                ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan sudah memiliki cuti pada periode yang sama.'
                ], 422);
            }

            Cuti::create([
                'karyawan_id' => $request->karyawan_id,
                'jenis_cuti' => $request->jenis_cuti,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jumlah_hari' => $jumlahHari,
                'keterangan' => $request->keterangan,
                'status' => 'diajukan'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dibuat.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cuti = Cuti::with(['karyawan.department', 'karyawan.jabatan', 'approver'])->findOrFail($id);
        return response()->json($cuti);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);

        // Hanya bisa edit jika status masih diajukan
        if ($cuti->status !== 'diajukan') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan cuti dengan status "diajukan" yang dapat diedit.'
            ], 422);
        }

        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_cuti' => 'required|in:tahunan,sakit,melahirkan,penting,lainnya',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:1000'
        ]);

        try {
            // Hitung jumlah hari
            $tanggalMulai = Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

            // Cek overlapping cuti (exclude current record)
            $overlapping = Cuti::where('karyawan_id', $request->karyawan_id)
                ->where('id', '!=', $id)
                ->where('status', '!=', 'ditolak')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                        ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                                ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan sudah memiliki cuti pada periode yang sama.'
                ], 422);
            }

            $cuti->update([
                'karyawan_id' => $request->karyawan_id,
                'jenis_cuti' => $request->jenis_cuti,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jumlah_hari' => $jumlahHari,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cuti = Cuti::findOrFail($id);

            // Hanya bisa hapus jika status masih diajukan
            if ($cuti->status !== 'diajukan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan cuti dengan status "diajukan" yang dapat dihapus.'
                ], 422);
            }

            $cuti->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve or reject cuti
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_persetujuan' => 'nullable|string|max:1000'
        ]);

        try {
            $cuti = Cuti::findOrFail($id);

            if ($cuti->status !== 'diajukan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan cuti dengan status "diajukan" yang dapat diproses.'
                ], 422);
            }

            $cuti->update([
                'status' => $request->status,
                'disetujui_oleh' => Auth::id(),
                'catatan_persetujuan' => $request->catatan_persetujuan
            ]);

            $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';

            return response()->json([
                'success' => true,
                'message' => "Pengajuan cuti berhasil {$statusText}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get departments for filter
     */
    public function getDepartments()
    {
        $departments = Department::orderBy('nama')->get();
        return response()->json($departments);
    }

    /**
     * Get employees by department
     */
    public function getEmployeesByDepartment($departmentId)
    {
        $karyawan = Karyawan::with('department')
            ->where('department_id', $departmentId)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return response()->json($karyawan);
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'Data_Cuti_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new CutiExport($request->all()), $fileName);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Cuti::with(['karyawan.department', 'karyawan.jabatan', 'approver']);

        // Apply same filters as index method
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('karyawan', function ($sub) use ($search) {
                    $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_cuti', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('jenis_cuti') && !empty($request->jenis_cuti)) {
            $query->where('jenis_cuti', $request->jenis_cuti);
        }

        if ($request->has('karyawan_id') && !empty($request->karyawan_id)) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->has('department_id') && !empty($request->department_id)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && !empty($request->tanggal_selesai)) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        $cuti = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('hr_karyawan.cuti.pdf.export', compact('cuti'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'sans-serif']);

        $fileName = 'Data_Cuti_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($fileName);
    }
}
