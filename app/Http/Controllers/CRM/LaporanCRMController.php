<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Prospek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanCRMController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan_crm_view');
    }

    /**
     * Display the CRM analytics dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Filter logic
        $filter_type = $request->input('filter_type', 'year');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $applyFilter = function ($q) use ($filter_type, $year, $month, $start_date, $end_date) {
            if ($filter_type === 'year') {
                $q->whereYear('created_at', $year);
            } elseif ($filter_type === 'month') {
                $q->whereYear('created_at', $year)->whereMonth('created_at', $month);
            } elseif ($filter_type === 'custom' && $start_date && $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date . ' 23:59:59']);
            } else {
                $q->whereYear('created_at', date('Y'));
            }
        };

        // Total Prospek per Kategori
        $totalAktif = Prospek::whereIn('status', ['baru', 'tertarik', 'negosiasi'])->where($applyFilter)->count();
        $totalWon = Prospek::where('status', 'menjadi_customer')->where($applyFilter)->count();
        $totalLost = Prospek::where('status', 'menolak')->where($applyFilter)->count();
        $totalAll = Prospek::where($applyFilter)->count();

        // Win Rate Calculation
        $winRate = $totalAll > 0 ? round(($totalWon / $totalAll) * 100, 1) : 0;

        // Total Deal Value in active pipeline
        $totalPotensiAktif = Prospek::whereIn('status', ['baru', 'tertarik', 'negosiasi'])->where($applyFilter)->sum('nilai_potensi');
        
        // Total Deal Value Won
        $totalPotensiWon = Prospek::where('status', 'menjadi_customer')->where($applyFilter)->sum('nilai_potensi');

        // Pipeline Funnel Distribution
        $funnelData = [
            'baru' => Prospek::where('status', 'baru')->where($applyFilter)->count(),
            'tertarik' => Prospek::where('status', 'tertarik')->where($applyFilter)->count(),
            'negosiasi' => Prospek::where('status', 'negosiasi')->where($applyFilter)->count(),
            'menjadi_customer' => $totalWon,
            'menolak' => $totalLost,
        ];

        // Pipeline Value Distribution
        $valueData = [
            'baru' => Prospek::where('status', 'baru')->where($applyFilter)->sum('nilai_potensi'),
            'tertarik' => Prospek::where('status', 'tertarik')->where($applyFilter)->sum('nilai_potensi'),
            'negosiasi' => Prospek::where('status', 'negosiasi')->where($applyFilter)->sum('nilai_potensi'),
            'menjadi_customer' => $totalPotensiWon,
        ];

        // Sales Performance
        $salesPerformance = User::whereHas('prospeks', $applyFilter)->withCount([
            'prospeks as total_prospek' => $applyFilter,
            'prospeks as won_prospek' => function ($query) use ($applyFilter) {
                $query->where('status', 'menjadi_customer');
                $applyFilter($query);
            },
            'prospeks as active_prospek' => function ($query) use ($applyFilter) {
                $query->whereIn('status', ['baru', 'tertarik', 'negosiasi']);
                $applyFilter($query);
            }
        ])->get()->map(function ($user) use ($applyFilter) {
            $user->win_rate = $user->total_prospek > 0 ? round(($user->won_prospek / $user->total_prospek) * 100, 1) : 0;
            $user->total_value_won = $user->prospeks()->where('status', 'menjadi_customer')->where($applyFilter)->sum('nilai_potensi');
            $user->total_value_active = $user->prospeks()->whereIn('status', ['baru', 'tertarik', 'negosiasi'])->where($applyFilter)->sum('nilai_potensi');
            return $user;
        })->sortByDesc('total_value_won')->values();

        return view('CRM.laporan_CRM.index', compact(
            'totalAktif',
            'totalWon',
            'totalLost',
            'winRate',
            'totalPotensiAktif',
            'totalPotensiWon',
            'funnelData',
            'valueData',
            'salesPerformance',
            'filter_type',
            'year',
            'month',
            'start_date',
            'end_date'
        ));
    }
}
