<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AbsensiImport implements ToCollection, WithHeadingRow
{
    protected $imported = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        Log::info('AbsensiImport: Processing ' . count($rows) . ' rows');

        foreach ($rows as $index => $row) {
            try {
                Log::info('AbsensiImport: Processing row ' . ($index + 1), $row->toArray());

                // Find karyawan by nama_lengkap or nip
                $karyawan = Karyawan::where('nama_lengkap', $row['nama_karyawan'] ?? '')
                    ->orWhere('nip', $row['nip'] ?? '')
                    ->first();

                if (!$karyawan) {
                    $error = "Baris " . ($index + 2) . ": Karyawan tidak ditemukan - " . ($row['nama_karyawan'] ?? $row['nip'] ?? 'N/A');
                    Log::warning('AbsensiImport: ' . $error);
                    $this->errors[] = $error;
                    continue;
                }

                // Parse tanggal
                $tanggal = null;
                if (isset($row['tanggal'])) {
                    try {
                        if (is_numeric($row['tanggal'])) {
                            // Excel date serial number
                            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
                        } else {
                            // String date
                            $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $error = "Baris " . ($index + 2) . ": Format tanggal tidak valid - " . ($row['tanggal'] ?? 'N/A');
                        Log::warning('AbsensiImport: ' . $error);
                        $this->errors[] = $error;
                        continue;
                    }
                }

                // Check if record already exists
                $exists = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

                if ($exists) {
                    $error = "Baris " . ($index + 2) . ": Data sudah ada untuk " . $karyawan->nama_lengkap . " pada tanggal " . $tanggal;
                    Log::warning('AbsensiImport: ' . $error);
                    $this->errors[] = $error;
                    continue;
                }

                $absensiData = [
                    'karyawan_id' => $karyawan->id,
                    'tanggal' => $tanggal,
                    'jam_masuk' => $row['jam_masuk'] ?? null,
                    'jam_keluar' => $row['jam_keluar'] ?? null,
                    'status' => $row['status'] ?? 'hadir',
                    'keterangan' => $row['keterangan'] ?? null
                ];

                // Cek keterlambatan untuk status hadir dengan jam masuk (skip jika jam masuk 00:00)
                if (($absensiData['status'] === 'hadir') && $absensiData['jam_masuk']) {
                    try {
                        $jamMasukString = '';

                        // Handle Excel time format
                        if (is_numeric($absensiData['jam_masuk'])) {
                            // Excel time serial number (fraction of day)
                            $totalSeconds = $absensiData['jam_masuk'] * 24 * 60 * 60;
                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $jamMasukString = sprintf('%02d:%02d', $hours, $minutes);
                        } else {
                            // Normalize various time formats
                            $jamMasukString = $this->normalizeTimeFormat($absensiData['jam_masuk']);
                        }

                        // Skip pengecekan keterlambatan jika jam masuk adalah 00:00 atau format tidak valid
                        if ($jamMasukString === '00:00' || empty($jamMasukString)) {
                            Log::info('AbsensiImport: Skipping lateness check for 00:00 or invalid time');
                        } else {
                            $jamMasuk = Carbon::createFromFormat('H:i', $jamMasukString);
                            $batasMasuk = Carbon::createFromFormat('H:i', '08:30');

                            if ($jamMasuk->gt($batasMasuk)) {
                                $menitTerlambat = $jamMasuk->diffInMinutes($batasMasuk);
                                $keteranganTerlambat = "Terlambat {$menitTerlambat} menit";

                                // Cek apakah keterangan ada dan tidak kosong (trim untuk menghilangkan spasi)
                                $keteranganExisting = trim($absensiData['keterangan'] ?? '');

                                if (!empty($keteranganExisting)) {
                                    $absensiData['keterangan'] = $keteranganExisting . '. ' . $keteranganTerlambat;
                                } else {
                                    $absensiData['keterangan'] = $keteranganTerlambat;
                                }
                            }
                        }

                        // Update jam_masuk dengan format yang sudah dinormalisasi
                        if (!empty($jamMasukString) && $jamMasukString !== '00:00') {
                            $absensiData['jam_masuk'] = $jamMasukString;
                        }
                    } catch (\Exception $e) {
                        // Jika format jam tidak valid, lewati pengecekan keterlambatan
                        Log::warning('AbsensiImport: Invalid time format, skipping lateness check', ['error' => $e->getMessage()]);
                    }
                }

                // Normalize jam_keluar juga jika ada
                if ($absensiData['jam_keluar']) {
                    try {
                        if (is_numeric($absensiData['jam_keluar'])) {
                            $totalSeconds = $absensiData['jam_keluar'] * 24 * 60 * 60;
                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $absensiData['jam_keluar'] = sprintf('%02d:%02d', $hours, $minutes);
                        } else {
                            $jamKeluarNormalized = $this->normalizeTimeFormat($absensiData['jam_keluar']);
                            if (!empty($jamKeluarNormalized) && $jamKeluarNormalized !== '00:00') {
                                $absensiData['jam_keluar'] = $jamKeluarNormalized;
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('AbsensiImport: Invalid jam_keluar format', ['error' => $e->getMessage()]);
                    }
                }

                Absensi::create($absensiData);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 2) . ": Error - " . $e->getMessage();
            }
        }
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Normalize various time formats to H:i format
     */
    private function normalizeTimeFormat($timeInput)
    {
        if (empty($timeInput)) {
            return '';
        }

        $timeString = trim($timeInput);

        // Remove seconds if present (HH:MM:SS or HH.MM.SS)
        $timeString = preg_replace('/(\d{1,2}[:.]\d{2})[:.]\d{2}/', '$1', $timeString);

        // Convert dots to colons (HH.MM to HH:MM)
        $timeString = str_replace('.', ':', $timeString);

        // Try to parse the time
        try {
            // Handle single digit hours/minutes
            if (preg_match('/^(\d{1,2}):(\d{1,2})$/', $timeString, $matches)) {
                $hours = intval($matches[1]);
                $minutes = intval($matches[2]);

                // Validate time
                if ($hours >= 0 && $hours <= 23 && $minutes >= 0 && $minutes <= 59) {
                    return sprintf('%02d:%02d', $hours, $minutes);
                }
            }

            // Try to parse with Carbon for other formats
            $carbonTime = Carbon::parse($timeString);
            return $carbonTime->format('H:i');
        } catch (\Exception $e) {
            Log::warning('AbsensiImport: Failed to normalize time format', [
                'input' => $timeInput,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }
}
