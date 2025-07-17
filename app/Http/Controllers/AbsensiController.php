<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\AbsensiExport;
use App\Exports\AbsensiTemplateExport;
use App\Imports\AbsensiImport;
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

            // Default ke hari ini jika tidak ada filter tanggal
            if (!$request->has('tanggal_mulai') || !$request->tanggal_mulai) {
                $request->merge(['tanggal_mulai' => now()->toDateString()]);
            }
            if (!$request->has('tanggal_akhir') || !$request->tanggal_akhir) {
                $request->merge(['tanggal_akhir' => now()->toDateString()]);
            }

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
            \Illuminate\Support\Facades\Log::error('Error in getAbsensiData: ' . $e->getMessage());
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

        $data = $request->all();

        // Cek keterlambatan jika status hadir dan ada jam masuk (skip jika jam masuk 00:00)
        if ($request->status === 'hadir' && $request->jam_masuk && $request->jam_masuk !== '00:00') {
            $jamMasuk = Carbon::createFromFormat('H:i', $request->jam_masuk);
            $batasMasuk = Carbon::createFromFormat('H:i', '08:30');

            if ($jamMasuk->gt($batasMasuk)) {
                $menitTerlambat = $jamMasuk->diffInMinutes($batasMasuk);
                $keteranganTerlambat = "Terlambat {$menitTerlambat} menit";

                // Cek apakah keterangan ada dan tidak kosong (trim untuk menghilangkan spasi)
                $keteranganExisting = trim($request->keterangan ?? '');

                if (!empty($keteranganExisting)) {
                    $data['keterangan'] = $keteranganExisting . '. ' . $keteranganTerlambat;
                } else {
                    $data['keterangan'] = $keteranganTerlambat;
                }
            }
        }

        Absensi::create($data);

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

        $data = $request->all();

        // Cek keterlambatan jika status hadir dan ada jam masuk (skip jika jam masuk 00:00)
        if ($request->status === 'hadir' && $request->jam_masuk && $request->jam_masuk !== '00:00') {
            $jamMasuk = Carbon::createFromFormat('H:i', $request->jam_masuk);
            $batasMasuk = Carbon::createFromFormat('H:i', '08:30');

            if ($jamMasuk->gt($batasMasuk)) {
                $menitTerlambat = $jamMasuk->diffInMinutes($batasMasuk);
                $keteranganTerlambat = "Terlambat {$menitTerlambat} menit";

                // Hapus keterangan terlambat lama jika ada
                $keteranganBersih = $request->keterangan ?
                    preg_replace('/\. ?Terlambat \d+ menit/', '', $request->keterangan) : '';
                $keteranganBersih = preg_replace('/^Terlambat \d+ menit\.? ?/', '', $keteranganBersih);
                $keteranganBersih = trim($keteranganBersih);

                // Gabungkan dengan keterangan yang sudah ada (jika ada dan tidak kosong)
                if (!empty($keteranganBersih)) {
                    $data['keterangan'] = $keteranganBersih . '. ' . $keteranganTerlambat;
                } else {
                    $data['keterangan'] = $keteranganTerlambat;
                }
            } else {
                // Jika tidak terlambat, hapus keterangan terlambat jika ada
                if ($request->keterangan) {
                    $keteranganBersih = preg_replace('/\. ?Terlambat \d+ menit/', '', $request->keterangan);
                    $keteranganBersih = preg_replace('/^Terlambat \d+ menit\.? ?/', '', $keteranganBersih);
                    $keteranganBersih = trim($keteranganBersih);
                    $data['keterangan'] = $keteranganBersih;
                }
            }
        } else if ($request->status === 'hadir' && $request->jam_masuk === '00:00') {
            // Jika jam masuk adalah 00:00, hapus keterangan terlambat jika ada
            if ($request->keterangan) {
                $keteranganBersih = preg_replace('/\. ?Terlambat \d+ menit/', '', $request->keterangan);
                $keteranganBersih = preg_replace('/^Terlambat \d+ menit\.? ?/', '', $keteranganBersih);
                $keteranganBersih = trim($keteranganBersih);
                $data['keterangan'] = $keteranganBersih;
            }
        }

        $absensi->update($data);

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
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $import = new AbsensiImport();

            Excel::import($import, $file);

            $imported = $import->getImported();
            $errors = $import->getErrors();

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

    public function downloadTemplate()
    {
        // Get a few active employees for template
        $karyawans = Karyawan::where('status', 'aktif')
            ->with('department')
            ->take(5)
            ->get();

        $templateData = [];
        $today = date('Y-m-d');

        foreach ($karyawans as $karyawan) {
            $templateData[] = [
                'nama_karyawan' => $karyawan->nama_lengkap,
                'tanggal' => $today,
                'jam_masuk' => '08:00',
                'jam_keluar' => '17:00',
                'status' => 'hadir',
                'keterangan' => ''
            ];
        }

        // Add some example variations
        if (count($templateData) > 1) {
            $templateData[1]['jam_masuk'] = '08:35';
            $templateData[1]['keterangan'] = ''; // Kosong untuk demo auto-fill keterlambatan
        }

        if (count($templateData) > 2) {
            $templateData[2]['jam_masuk'] = '';
            $templateData[2]['jam_keluar'] = '';
            $templateData[2]['status'] = 'sakit';
            $templateData[2]['keterangan'] = 'Sakit flu';
        }

        if (count($templateData) > 3) {
            $templateData[3]['jam_masuk'] = '09:15';
            $templateData[3]['keterangan'] = 'Ada urusan penting'; // Akan ditambah otomatis: "Ada urusan penting. Terlambat 45 menit"
        }

        if (count($templateData) > 4) {
            $templateData[4]['jam_masuk'] = '00:00';
            $templateData[4]['jam_keluar'] = '00:00';
            $templateData[4]['keterangan'] = 'Jam 00:00 tidak akan dianggap sebagai jam masuk/keluar';
        }

        // Add example with different time formats if there are more employees
        if (count($karyawans) > 5) {
            $templateData[] = [
                'nama_karyawan' => 'Contoh Format Waktu',
                'tanggal' => $today,
                'jam_masuk' => '08.45.00',
                'jam_keluar' => '17.30',
                'status' => 'hadir',
                'keterangan' => 'Format dengan titik dan detik (akan dinormalisasi)'
            ];
        }

        return Excel::download(new AbsensiTemplateExport($templateData), 'template-import-absensi.xlsx');
    }
}
