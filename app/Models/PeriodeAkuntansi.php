<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodeAkuntansi extends Model
{
    use HasFactory;

    protected $table = 'periode_akuntansi';

    protected $fillable = [
        'nama',
        'tahun',
        'bulan',
        'tanggal_mulai',
        'tanggal_akhir',
        'status',
        'tanggal_tutup',
        'closed_by',
        'catatan_penutupan',
        'keterangan',
        'is_year_end'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_akhir' => 'datetime',
        'tanggal_tutup' => 'datetime',
        'is_year_end' => 'boolean'
    ];

    /**
     * Relasi ke user yang menutup periode
     */
    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Scope untuk periode yang masih terbuka
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope untuk periode yang sudah ditutup
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope untuk periode yang dikunci
     */
    public function scopeLocked($query)
    {
        return $query->where('status', 'locked');
    }

    /**
     * Get current active periode
     */
    public static function getCurrentPeriode()
    {
        $today = Carbon::today();

        return static::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_akhir', '>=', $today)
            ->where('status', 'open')
            ->first();
    }

    /**
     * Check if periode allows posting
     */
    public function allowsPosting()
    {
        return $this->status === 'open';
    }

    /**
     * Check if date is within this periode
     */
    public function containsDate($date)
    {
        $checkDate = Carbon::parse($date)->format('Y-m-d');
        $startDate = $this->tanggal_mulai->format('Y-m-d');
        $endDate = $this->tanggal_akhir->format('Y-m-d');

        return $checkDate >= $startDate && $checkDate <= $endDate;
    }

    /**
     * Close the periode
     */
    public function close($userId = null, $notes = null)
    {
        $this->update([
            'status' => 'closed',
            'tanggal_tutup' => Carbon::today(),
            'closed_by' => $userId ?: (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null),
            'catatan_penutupan' => $notes
        ]);
    }

    /**
     * Lock the periode
     */
    public function lock()
    {
        $this->update(['status' => 'locked']);
    }

    /**
     * Reopen the periode
     */
    public function reopen()
    {
        $this->update([
            'status' => 'open',
            'tanggal_tutup' => null,
            'closed_by' => null,
            'catatan_penutupan' => null
        ]);
    }

    /**
     * Generate periode for a year
     */
    public static function generateYearlyPeriodes($year)
    {
        $periodes = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            $periode = static::create([
                'nama' => $startDate->format('Y-m'),
                'tanggal_mulai' => $startDate,
                'tanggal_akhir' => $endDate,
                'status' => 'open',
                'is_year_end' => $month === 12
            ]);

            $periodes[] = $periode;
        }

        return $periodes;
    }

    /**
     * Check if any journal entries exist in this periode
     */
    public function hasJournalEntries()
    {
        return \App\Models\JurnalUmum::whereBetween('tanggal', [
            $this->tanggal_mulai,
            $this->tanggal_akhir
        ])->exists();
    }

    /**
     * Get periode stats
     */
    public function getStats()
    {
        $totalEntries = \App\Models\JurnalUmum::whereBetween('tanggal', [
            $this->tanggal_mulai,
            $this->tanggal_akhir
        ])->count();

        $postedEntries = \App\Models\JurnalUmum::whereBetween('tanggal', [
            $this->tanggal_mulai,
            $this->tanggal_akhir
        ])->where('is_posted', true)->count();

        $totalTransactions = \App\Models\JurnalUmum::whereBetween('tanggal', [
            $this->tanggal_mulai,
            $this->tanggal_akhir
        ])->distinct('no_referensi')->count();

        return [
            'total_entries' => $totalEntries,
            'posted_entries' => $postedEntries,
            'draft_entries' => $totalEntries - $postedEntries,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Get periode for specific date
     */
    public static function getPeriodeForDate($date)
    {
        $checkDate = Carbon::parse($date);

        return static::where('tanggal_mulai', '<=', $checkDate)
            ->where('tanggal_akhir', '>=', $checkDate)
            ->first();
    }

    /**
     * Create monthly periods for a year
     */
    public static function createMonthlyPeriods($year = null)
    {
        $year = $year ?: Carbon::now()->year;
        $periods = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            // Check if period already exists
            $existing = static::where('tanggal_mulai', $startDate->format('Y-m-d'))
                ->where('tanggal_akhir', $endDate->format('Y-m-d'))
                ->first();

            if (!$existing) {
                $period = static::create([
                    'nama' => $startDate->format('F Y'), // e.g., "January 2025"
                    'tanggal_mulai' => $startDate,
                    'tanggal_akhir' => $endDate,
                    'status' => 'open',
                    'is_year_end' => $month === 12
                ]);
                $periods[] = $period;
            }
        }

        return $periods;
    }

    /**
     * Get or create current period based on today's date
     */
    public static function getCurrentOrCreatePeriod()
    {
        $today = Carbon::today();

        // Try to find existing period
        $period = static::getPeriodeForDate($today);

        if (!$period) {
            // Create monthly period for current month
            $startDate = $today->copy()->startOfMonth();
            $endDate = $today->copy()->endOfMonth();

            $period = static::create([
                'nama' => $startDate->format('F Y'),
                'tanggal_mulai' => $startDate,
                'tanggal_akhir' => $endDate,
                'status' => 'open',
                'is_year_end' => $startDate->month === 12
            ]);
        }

        return $period;
    }

    /**
     * Auto create next period when current period is about to end
     */
    public static function createNextPeriod()
    {
        $currentPeriod = static::getCurrentPeriode();

        if ($currentPeriod) {
            $nextStart = Carbon::parse($currentPeriod->tanggal_akhir)->addDay();
            $nextEnd = $nextStart->copy()->endOfMonth();

            // Check if next period already exists
            $existing = static::where('tanggal_mulai', $nextStart->format('Y-m-d'))
                ->where('tanggal_akhir', $nextEnd->format('Y-m-d'))
                ->first();

            if (!$existing) {
                return static::create([
                    'nama' => $nextStart->format('F Y'),
                    'tanggal_mulai' => $nextStart,
                    'tanggal_akhir' => $nextEnd,
                    'status' => 'open',
                    'is_year_end' => $nextStart->month === 12
                ]);
            }

            return $existing;
        }

        return null;
    }

    /**
     * Auto check and create periods for upcoming months
     */
    public static function autoCreateUpcomingPeriods($monthsAhead = 3)
    {
        $created = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i <= $monthsAhead; $i++) {
            $targetDate = $currentDate->copy()->addMonths($i);
            $year = $targetDate->year;
            $month = $targetDate->month;

            // Check if period already exists
            $existing = static::where('tahun', $year)
                ->where('bulan', $month)
                ->first();

            if (!$existing) {
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();

                $period = static::create([
                    'nama' => $startDate->format('F Y'),
                    'tahun' => $year,
                    'bulan' => $month,
                    'tanggal_mulai' => $startDate,
                    'tanggal_akhir' => $endDate,
                    'status' => 'open',
                    'keterangan' => 'Auto-created by system',
                    'is_year_end' => $month === 12
                ]);

                $created[] = $period;
            }
        }

        return $created;
    }

    /**
     * Check if periods exist for a specific year
     */
    public static function yearHasAllPeriods($year)
    {
        $existingCount = static::where('tahun', $year)->count();
        return $existingCount >= 12;
    }

    /**
     * Get missing months for a specific year
     */
    public static function getMissingMonthsForYear($year)
    {
        $existingMonths = static::where('tahun', $year)
            ->pluck('bulan')
            ->toArray();

        $allMonths = range(1, 12);
        return array_diff($allMonths, $existingMonths);
    }

    /**
     * Auto create missing periods for a specific year
     */
    public static function autoCreateMissingPeriodsForYear($year)
    {
        $missingMonths = static::getMissingMonthsForYear($year);
        $created = [];

        foreach ($missingMonths as $month) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            $period = static::create([
                'nama' => $startDate->format('F Y'),
                'tahun' => $year,
                'bulan' => $month,
                'tanggal_mulai' => $startDate,
                'tanggal_akhir' => $endDate,
                'status' => 'open',
                'keterangan' => 'Auto-created by system',
                'is_year_end' => $month === 12
            ]);

            $created[] = $period;
        }

        return $created;
    }
}
