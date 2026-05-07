<?php

namespace App\Http\Controllers\hr_karyawan;

use App\Http\Controllers\Controller;
use App\Models\Penggajian;
use App\Models\LogAktivitas;
use App\Traits\HasPDFQRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SlipGajiController extends Controller
{
    use HasPDFQRCode;

    public function index()
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            return redirect()->route('dashboard')->with('error', 'Data karyawan tidak ditemukan untuk akun ini.');
        }

        // Only show approved or paid payrolls
        $slips = Penggajian::where('karyawan_id', $karyawan->id)
            ->whereIn('status', ['disetujui', 'dibayar'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Slip Gaji', 'url' => '#'],
        ];

        return view('hr_karyawan.slip_gaji.index', compact('slips', 'breadcrumbs'));
    }

    public function downloadPdf($id)
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            abort(403, 'Unauthorized action.');
        }

        $penggajian = Penggajian::with(['karyawan.department', 'karyawan.jabatan', 'komponenGaji', 'approver'])
            ->where('id', $id)
            ->where('karyawan_id', $karyawan->id)
            ->whereIn('status', ['disetujui', 'dibayar'])
            ->firstOrFail();

        // Increase the execution time limit for PDF generation
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

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

        $filename = 'Slip-Gaji-' . $penggajian->karyawan->nama_lengkap . '-' . $monthName . '-' . $penggajian->tahun . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate QR codes for slip gaji PDF signatures (Copied from PenggajianController)
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
}
