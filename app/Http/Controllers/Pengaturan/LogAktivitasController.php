<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the log aktivitas.
     */
    public function index(Request $request)
    {
        // Build query with eager loading for better performance
        $query = LogAktivitas::with(['user:id,name,email'])
            ->select(['id', 'user_id', 'aktivitas', 'modul', 'data_id', 'ip_address', 'detail', 'created_at']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by module
        if ($request->filled('modul')) {
            $query->where('modul', 'like', '%' . $request->modul . '%');
        }

        // Filter by activity
        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Search global
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                    ->orWhere('modul', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $allowedSortFields = [
            'created_at',
            'user_id',
            'aktivitas',
            'modul',
            'ip_address'
        ];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get paginated results
        $logAktivitas = $query->paginate(20)->withQueryString();

        // Get filter options with optimized queries
        $users = User::select(['id', 'name', 'email'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $modules = LogAktivitas::distinct()
            ->pluck('modul')
            ->filter()
            ->sort()
            ->values();

        $activities = LogAktivitas::distinct()
            ->pluck('aktivitas')
            ->filter()
            ->sort()
            ->values();

        // Get statistics
        $statistics = $this->getStatistics($request);

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Log Aktivitas', 'url' => route('pengaturan.log-aktivitas.index')],
        ];

        $currentPage = 'Log Aktivitas';

        return view('pengaturan.log-aktivitas.index', compact(
            'logAktivitas',
            'users',
            'modules',
            'activities',
            'statistics',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Show the specified log aktivitas.
     */
    public function show($id)
    {
        // Find the log aktivitas with explicit loading
        $logAktivitas = LogAktivitas::with(['user:id,name,email'])->findOrFail($id);

        // Get related logs - logs with same data_id and modul
        $relatedLogs = collect();
        if ($logAktivitas->data_id && $logAktivitas->modul) {
            $relatedLogs = LogAktivitas::with(['user:id,name,email'])
                ->where('data_id', $logAktivitas->data_id)
                ->where('modul', $logAktivitas->modul)
                ->where('id', '!=', $logAktivitas->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get user's recent activities
        $userRecentLogs = collect();
        if ($logAktivitas->user_id) {
            $userRecentLogs = LogAktivitas::with(['user:id,name,email'])
                ->where('user_id', $logAktivitas->user_id)
                ->where('id', '!=', $logAktivitas->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Log Aktivitas', 'url' => route('pengaturan.log-aktivitas.index')],
            ['label' => 'Detail Log #' . $logAktivitas->id, 'url' => '#'],
        ];

        $currentPage = 'Detail Log Aktivitas';

        return view('pengaturan.log-aktivitas.show', compact(
            'logAktivitas',
            'relatedLogs',
            'userRecentLogs',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Get statistics for dashboard cards
     */
    private function getStatistics(Request $request)
    {
        $query = LogAktivitas::query();

        // Apply same filters as main query for consistent stats
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('modul')) {
            $query->where('modul', 'like', '%' . $request->modul . '%');
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $totalLogs = $query->count();
        $todayLogs = (clone $query)->whereDate('created_at', Carbon::today())->count();
        $thisWeekLogs = (clone $query)->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        $thisMonthLogs = (clone $query)->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();

        // Top modules
        $topModules = (clone $query)->select('modul', DB::raw('count(*) as total'))
            ->groupBy('modul')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Top users
        $topUsers = (clone $query)->select('user_id', DB::raw('count(*) as total'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Activity trends (last 7 days)
        $activityTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = LogAktivitas::whereDate('created_at', $date->format('Y-m-d'))->count();
            $activityTrends[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'count' => $count
            ];
        }

        return [
            'total' => $totalLogs,
            'today' => $todayLogs,
            'this_week' => $thisWeekLogs,
            'this_month' => $thisMonthLogs,
            'top_modules' => $topModules,
            'top_users' => $topUsers,
            'activity_trends' => $activityTrends
        ];
    }

    /**
     * Export log aktivitas
     */
    public function export(Request $request)
    {
        $query = LogAktivitas::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('modul')) {
            $query->where('modul', 'like', '%' . $request->modul . '%');
        }
        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'log-aktivitas-' . date('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Tanggal & Waktu',
                'User',
                'Email User',
                'Aktivitas',
                'Modul',
                'Data ID',
                'IP Address',
                'Detail'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user->name ?? 'User Tidak Ditemukan',
                    $log->user->email ?? '-',
                    $log->aktivitas,
                    $log->modul,
                    $log->data_id ?? '-',
                    $log->ip_address ?? '-',
                    $log->detail ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete log aktivitas
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:log_aktivitas,id',
        ]);

        $deleted = LogAktivitas::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "$deleted log aktivitas berhasil dihapus",
            'deleted_count' => $deleted
        ]);
    }

    /**
     * Clean old logs (older than specified days)
     */
    public function cleanOldLogs(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        $deleted = LogAktivitas::where('created_at', '<', $cutoffDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "$deleted log aktivitas lama berhasil dihapus",
            'deleted_count' => $deleted
        ]);
    }
}
