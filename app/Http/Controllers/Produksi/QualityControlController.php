<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\QualityControl;
use App\Models\QualityControlDetail;
use App\Models\WorkOrder;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QualityControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:quality_control.view')->only(['index', 'show', 'report']);
        $this->middleware('permission:quality_control.process')->only(['approve', 'reject']);
        $this->middleware('permission:quality_control.export')->only(['exportPdf']);
    }

    /**
     * Menampilkan daftar quality control
     */
    public function index(Request $request)
    {
        $query = QualityControl::with(['workOrder', 'workOrder.produk', 'inspector']);

        $sort = $request->get('sort', 'tanggal_inspeksi');
        $direction = $request->get('direction', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('workOrder', function ($sq) use ($search) {
                    $sq->where('nomor', 'like', "%{$search}%");
                })
                    ->orWhereHas('workOrder.produk', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan periode
        if ($request->filled('periode') && $request->periode !== 'all') {
            $periode = explode(' - ', $request->periode);
            if (count($periode) == 2) {
                $start = date('Y-m-d', strtotime($periode[0]));
                $end = date('Y-m-d', strtotime($periode[1]));
                $query->whereBetween('tanggal_inspeksi', [$start, $end]);
            }
        }

        // Sorting
        if ($sort === 'tanggal_inspeksi' || $sort === 'status') {
            $query->orderBy($sort, $direction);
        } else if ($sort === 'work_order') {
            $query->join('work_order', 'quality_control.work_order_id', '=', 'work_order.id')
                ->orderBy('work_order.nomor', $direction)
                ->select('quality_control.*');
        } else if ($sort === 'produk') {
            $query->join('work_order', 'quality_control.work_order_id', '=', 'work_order.id')
                ->join('produk', 'work_order.produk_id', '=', 'produk.id')
                ->orderBy('produk.nama', $direction)
                ->select('quality_control.*');
        } else if ($sort === 'inspector') {
            $query->join('users', 'quality_control.inspector_id', '=', 'users.id')
                ->orderBy('users.name', $direction)
                ->select('quality_control.*');
        } else {
            $query->orderBy('tanggal_inspeksi', 'desc');
        }

        $qualityControls = $query->paginate(10);

        return view('produksi.quality-control.index', compact('qualityControls'));
    }

    /**
     * Menampilkan detail quality control
     */
    public function show($id)
    {
        $qc = QualityControl::with([
            'workOrder',
            'workOrder.produk',
            'workOrder.satuan',
            'detail',
            'inspector'
        ])->findOrFail($id);

        return view('produksi.quality-control.show', compact('qc'));
    }

    /**
     * Generate laporan quality control
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $qcList = QualityControl::with(['workOrder', 'workOrder.produk', 'inspector'])
            ->whereBetween('tanggal_inspeksi', [$startDate, $endDate])
            ->get();

        // Group by product
        $produkStats = [];
        $totalInspected = 0;
        $totalPassed = 0;
        $totalFailed = 0;

        foreach ($qcList as $qc) {
            $produkId = $qc->workOrder->produk_id;
            $produkNama = $qc->workOrder->produk->nama;

            if (!isset($produkStats[$produkId])) {
                $produkStats[$produkId] = [
                    'nama' => $produkNama,
                    'total_inspeksi' => 0,
                    'total_lolos' => 0,
                    'total_gagal' => 0,
                    'pass_rate' => 0
                ];
            }

            $produkStats[$produkId]['total_inspeksi'] += $qc->jumlah_lolos + $qc->jumlah_gagal;
            $produkStats[$produkId]['total_lolos'] += $qc->jumlah_lolos;
            $produkStats[$produkId]['total_gagal'] += $qc->jumlah_gagal;

            $totalInspected += $qc->jumlah_lolos + $qc->jumlah_gagal;
            $totalPassed += $qc->jumlah_lolos;
            $totalFailed += $qc->jumlah_gagal;
        }

        // Calculate pass rate for each product
        foreach ($produkStats as $produkId => $stats) {
            if ($stats['total_inspeksi'] > 0) {
                $produkStats[$produkId]['pass_rate'] = round(($stats['total_lolos'] / $stats['total_inspeksi']) * 100, 2);
            }
        }

        // Sort by pass rate (descending)
        uasort($produkStats, function ($a, $b) {
            return $b['pass_rate'] <=> $a['pass_rate'];
        });

        // Calculate overall pass rate
        $overallPassRate = $totalInspected > 0 ? round(($totalPassed / $totalInspected) * 100, 2) : 0;

        return view('produksi.quality-control.report', compact(
            'produkStats',
            'qcList',
            'startDate',
            'endDate',
            'totalInspected',
            'totalPassed',
            'totalFailed',
            'overallPassRate'
        ));
    }

    /**
     * Export laporan quality control sebagai PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $qcList = QualityControl::with(['workOrder', 'workOrder.produk', 'inspector'])
            ->whereBetween('tanggal_inspeksi', [$startDate, $endDate])
            ->get();

        // Group by product
        $produkStats = [];
        $totalInspected = 0;
        $totalPassed = 0;
        $totalFailed = 0;

        foreach ($qcList as $qc) {
            $produkId = $qc->workOrder->produk_id;
            $produkNama = $qc->workOrder->produk->nama;

            if (!isset($produkStats[$produkId])) {
                $produkStats[$produkId] = [
                    'nama' => $produkNama,
                    'total_inspeksi' => 0,
                    'total_lolos' => 0,
                    'total_gagal' => 0,
                    'pass_rate' => 0
                ];
            }

            $produkStats[$produkId]['total_inspeksi'] += $qc->jumlah_lolos + $qc->jumlah_gagal;
            $produkStats[$produkId]['total_lolos'] += $qc->jumlah_lolos;
            $produkStats[$produkId]['total_gagal'] += $qc->jumlah_gagal;

            $totalInspected += $qc->jumlah_lolos + $qc->jumlah_gagal;
            $totalPassed += $qc->jumlah_lolos;
            $totalFailed += $qc->jumlah_gagal;
        }

        // Calculate pass rate for each product
        foreach ($produkStats as $produkId => $stats) {
            if ($stats['total_inspeksi'] > 0) {
                $produkStats[$produkId]['pass_rate'] = round(($stats['total_lolos'] / $stats['total_inspeksi']) * 100, 2);
            }
        }

        // Sort by pass rate (descending)
        uasort($produkStats, function ($a, $b) {
            return $b['pass_rate'] <=> $a['pass_rate'];
        });

        // Calculate overall pass rate
        $overallPassRate = $totalInspected > 0 ? round(($totalPassed / $totalInspected) * 100, 2) : 0;

        $pdf = \PDF::loadView('produksi.quality-control.pdf', compact(
            'produkStats',
            'qcList',
            'startDate',
            'endDate',
            'totalInspected',
            'totalPassed',
            'totalFailed',
            'overallPassRate'
        ));

        return $pdf->download('Laporan_QC_' . $startDate . '_' . $endDate . '.pdf');
    }
}
