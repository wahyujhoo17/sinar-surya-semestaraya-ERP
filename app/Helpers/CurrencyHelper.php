<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function terbilang($number)
    {
        $number = abs($number);
        $letters = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
        $result = '';

        if ($number < 12) {
            $result = $letters[$number];
        } elseif ($number < 20) {
            $result = self::terbilang($number - 10) . ' belas';
        } elseif ($number < 100) {
            $result = self::terbilang(floor($number / 10)) . ' puluh ' . self::terbilang($number % 10);
        } elseif ($number < 200) {
            $result = 'seratus ' . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            $result = self::terbilang(floor($number / 100)) . ' ratus ' . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            $result = 'seribu ' . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $result = self::terbilang(floor($number / 1000)) . ' ribu ' . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $result = self::terbilang(floor($number / 1000000)) . ' juta ' . self::terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $result = self::terbilang(floor($number / 1000000000)) . ' milyar ' . self::terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            $result = self::terbilang(floor($number / 1000000000000)) . ' trilyun ' . self::terbilang($number % 1000000000000);
        }

        return trim($result);
    }
}
