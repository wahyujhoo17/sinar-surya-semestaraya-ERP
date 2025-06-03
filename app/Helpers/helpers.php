<?php
// filepath: app/Helpers/helpers.php


if (!function_exists('isMenuActive')) {
    /**
     * Check if the current route matches any of the given patterns.
     *
     * @param array|string $patterns Route name patterns (e.g., 'users.*' or ['users.*', 'profile'])
     * @param string $activeClass CSS class to return if active (optional)
     * @param string $inactiveClass CSS class to return if inactive (optional)
     * @return bool|string Returns boolean if no classes provided, otherwise the corresponding class string.
     */
    function isMenuActive($patterns, $activeClass = null, $inactiveClass = null)
    {
        $patterns = (array) $patterns;
        $isActive = false;

        // Use the fully qualified namespace for Route facade
        foreach ($patterns as $pattern) {
            if (\Illuminate\Support\Facades\Route::is($pattern)) {
                $isActive = true;
                break;
            }
        }

        if ($activeClass === null && $inactiveClass === null) {
            return $isActive;
        }

        return $isActive ? $activeClass : $inactiveClass;
    }
}

if (!function_exists('tanggal_indo')) {
    /**
     * Format tanggal ke format Indonesia (dd/mm/yyyy atau dd Month yyyy)
     * @param string|\DateTimeInterface $tanggal
     * @param bool $withMonthName
     * @return string
     */
    function tanggal_indo($tanggal, $withMonthName = false)
    {
        if (!$tanggal) return '';
        if ($tanggal instanceof \DateTimeInterface) {
            $tanggal = $tanggal->format('Y-m-d');
        }
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $parts = explode('-', $tanggal);
        if (count($parts) !== 3) return $tanggal;
        if ($withMonthName) {
            return ltrim($parts[2], '0') . ' ' . $bulan[(int)$parts[1]] . ' ' . $parts[0];
        }
        return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    }
}

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Rupiah currency format.
     *
     * @param float $number
     * @param bool $withRpPrefix
     * @return string
     */
    function format_rupiah($number, $withRpPrefix = true)
    {
        if (!is_numeric($number)) {
            return $number; // or return 'Invalid Number';
        }
        $formatted = number_format($number, 0, ',', '.');
        return $withRpPrefix ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('terbilang')) {
    /**
     * Convert number to Indonesian words
     * 
     * @param float $number
     * @return string
     */
    function terbilang($number)
    {
        if (!is_numeric($number)) {
            return 'bukan angka';
        }

        if ($number < 0) {
            return 'minus ' . terbilang(abs($number));
        }

        $number = floor($number);

        $words = '';

        $digits = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($number < 12) {
            $words = $digits[$number];
        } elseif ($number < 20) {
            $words = $digits[$number - 10] . ' belas';
        } elseif ($number < 100) {
            $words = $digits[floor($number / 10)] . ' puluh ' . $digits[$number % 10];
        } elseif ($number < 200) {
            $words = 'seratus ' . terbilang($number - 100);
        } elseif ($number < 1000) {
            $words = $digits[floor($number / 100)] . ' ratus ' . terbilang($number % 100);
        } elseif ($number < 2000) {
            $words = 'seribu ' . terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $words = terbilang(floor($number / 1000)) . ' ribu ' . terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $words = terbilang(floor($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $words = terbilang(floor($number / 1000000000)) . ' milyar ' . terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            $words = terbilang(floor($number / 1000000000000)) . ' trilyun ' . terbilang($number % 1000000000000);
        }

        return trim($words);
    }
}
