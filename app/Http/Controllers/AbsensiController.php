<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();

        if ($request->ajax() || $request->wantsJson()) {
            return $this->getAbsensiData($request);
        }

        return view('hr_karyawan.absensi_kehadiran.index', compact('karyawans'));
    }

    private function getAbsensiData(Request $request)
    {
        try {
            $query = Absensi::with(['karyawan.department'])
                ->join('karyawan', 'absensi.karyawan_id', '=', 'karyawan.id')
                ->leftJoin('department', 'karyawan.department_id', '=', 'department.id')
                ->select('absensi.*', 'karyawan.nama_lengkap as nama_karyawan', 'department.nama as nama_department');

            // Filter berdasarkan tanggal
            if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
                $query->whereDate('absensi.tanggal', '>=', $request->tanggal_mulai);
            }

            if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
                $query->whereDate('absensi.tanggal', '<=', $request->tanggal_akhir);
            }

            // Filter berdasarkan karyawan
            if ($request->has('karyawan_id') && $request->karyawan_id) {
                $query->where('absensi.karyawan_id', $request->karyawan_id);
            }

            // Filter berdasarkan status
            if ($request->has('status') && $request->status) {
                $query->where('absensi.status', $request->status);
            }

            // Filter berdasarkan departemen
            if ($request->has('departemen') && $request->departemen) {
                $query->where('department.nama', $request->departemen);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('karyawan.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('department.nama', 'like', "%{$search}%")
                        ->orWhere('absensi.keterangan', 'like', "%{$search}%");
                });
            }

            $absensis = $query->orderBy('absensi.tanggal', 'desc')
                ->orderBy('karyawan.nama_lengkap')
                ->paginate(15);

            return response()->json([
                'data' => $absensis->items(),
                'pagination' => [
                    'current_page' => $absensis->currentPage(),
                    'last_page' => $absensis->lastPage(),
                    'per_page' => $absensis->perPage(),
                    'total' => $absensis->total(),
                    'from' => $absensis->firstItem(),
                    'to' => $absensis->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getAbsensiData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        return view('hr_karyawan.absensi_kehadiran.create', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,sakit,izin,cuti,alpha',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Cek duplikasi
        $exists = Absensi::where('karyawan_id', $request->karyawan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi untuk karyawan ini pada tanggal tersebut sudah ada.'
            ], 422);
        }

        Absensi::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil ditambahkan.'
        ]);
    }

    public function show($id)
    {
        $absensi = Absensi::with('karyawan')->findOrFail($id);
        return response()->json($absensi);
    }

    public function edit($id)
    {
        $absensi = Absensi::with('karyawan')->findOrFail($id);
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();

        return view('hr_karyawan.absensi_kehadiran.edit', compact('absensi', 'karyawans'));
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,sakit,izin,cuti,alpha',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Cek duplikasi (kecuali record yang sedang diupdate)
        $exists = Absensi::where('karyawan_id', $request->karyawan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi untuk karyawan ini pada tanggal tersebut sudah ada.'
            ], 422);
        }

        $absensi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus.'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['tanggal_mulai', 'tanggal_akhir', 'karyawan_id', 'status', 'departemen']);

        return Excel::download(new AbsensiExport($filters), 'data-absensi-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Absensi::with(['karyawan.department'])
            ->join('karyawan', 'absensi.karyawan_id', '=', 'karyawan.id')
            ->leftJoin('department', 'karyawan.department_id', '=', 'department.id')
            ->select('absensi.*', 'karyawan.nama_lengkap as nama_karyawan', 'department.nama as nama_department');

        // Apply same filters as in getAbsensiData
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('absensi.tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('absensi.tanggal', '<=', $request->tanggal_akhir);
        }

        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('absensi.karyawan_id', $request->karyawan_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('absensi.status', $request->status);
        }

        if ($request->has('departemen') && $request->departemen) {
            $query->where('department.nama', $request->departemen);
        }

        $absensis = $query->orderBy('absensi.tanggal', 'desc')
            ->orderBy('karyawan.nama_lengkap')
            ->get();

        $pdf = Pdf::loadView('hr_karyawan.absensi_kehadiran.pdf', compact('absensis'));

        return $pdf->download('laporan-absensi-' . date('Y-m-d') . '.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);

            $imported = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                if (count($row) < 4) continue; // Skip invalid rows

                $rowData = array_combine($header, $row);

                // Find karyawan by nama or nip
                $karyawan = Karyawan::where('nama_lengkap', $rowData['nama_karyawan'] ?? '')
                    ->orWhere('nip', $rowData['nip'] ?? '')
                    ->first();

                if (!$karyawan) {
                    $errors[] = "Baris " . ($index + 2) . ": Karyawan tidak ditemukan";
                    continue;
                }

                // Check if record already exists
                $exists = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereDate('tanggal', $rowData['tanggal'] ?? '')
                    ->exists();

                if ($exists) {
                    $errors[] = "Baris " . ($index + 2) . ": Data sudah ada";
                    continue;
                }

                Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'tanggal' => $rowData['tanggal'] ?? null,
                    'jam_masuk' => $rowData['jam_masuk'] ?? null,
                    'jam_keluar' => $rowData['jam_keluar'] ?? null,
                    'status' => $rowData['status'] ?? 'hadir',
                    'keterangan' => $rowData['keterangan'] ?? null
                ]);

                $imported++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimpor {$imported} data absensi.",
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDepartemen()
    {
        $departemen = Department::select('nama')
            ->distinct()
            ->whereNotNull('nama')
            ->orderBy('nama')
            ->pluck('nama');

        return response()->json($departemen);
    }
}
