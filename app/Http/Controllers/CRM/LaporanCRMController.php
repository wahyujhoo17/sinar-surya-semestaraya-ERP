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
        $this->middleware('permission:prospek_lead.view');
    }

    /**
     * Display the CRM analytics dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Total Prospek per Kategori
        $totalAktif = Prospek::whereIn('status', ['baru', 'tertarik', 'negosiasi'])->count();
        $totalWon = Prospek::where('status', 'menjadi_customer')->count();
        $totalLost = Prospek::where('status', 'menolak')->count();
        $totalAll = Prospek::count();

        // Win Rate Calculation
        $winRate = $totalAll > 0 ? round(($totalWon / $totalAll) * 100, 1) : 0;

        // Total Deal Value in active pipeline
        $totalPotensiAktif = Prospek::whereIn('status', ['baru', 'tertarik', 'negosiasi'])->sum('nilai_potensi');
        
        // Total Deal Value Won
        $totalPotensiWon = Prospek::where('status', 'menjadi_customer')->sum('nilai_potensi');

        // Pipeline Funnel Distribution
        $funnelData = [
            'baru' => Prospek::where('status', 'baru')->count(),
            'tertarik' => Prospek::where('status', 'tertarik')->count(),
            'negosiasi' => Prospek::where('status', 'negosiasi')->count(),
            'menjadi_customer' => $totalWon,
            'menolak' => $totalLost,
        ];

        // Pipeline Value Distribution
        $valueData = [
            'baru' => Prospek::where('status', 'baru')->sum('nilai_potensi'),
            'tertarik' => Prospek::where('status', 'tertarik')->sum('nilai_potensi'),
            'negosiasi' => Prospek::where('status', 'negosiasi')->sum('nilai_potensi'),
            'menjadi_customer' => $totalPotensiWon,
        ];

        // Sales Performance
        $salesPerformance = User::whereHas('prospeks')->withCount([
            'prospeks as total_prospek',
            'prospeks as won_prospek' => function ($query) {
                $query->where('status', 'menjadi_customer');
            },
            'prospeks as active_prospek' => function ($query) {
                $query->whereIn('status', ['baru', 'tertarik', 'negosiasi']);
            }
        ])->get()->map(function ($user) {
            $user->win_rate = $user->total_prospek > 0 ? round(($user->won_prospek / $user->total_prospek) * 100, 1) : 0;
            $user->total_value_won = $user->prospeks()->where('status', 'menjadi_customer')->sum('nilai_potensi');
            $user->total_value_active = $user->prospeks()->whereIn('status', ['baru', 'tertarik', 'negosiasi'])->sum('nilai_potensi');
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
            'salesPerformance'
        ));
    }
}
