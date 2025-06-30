<?php

namespace App\Traits;

use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait AutomaticJournalEntry
{
    /**
     * Membuat entri jurnal umum secara otomatis dengan sinkronisasi saldo kas/bank.
     *
     * @param array $entries Array berisi entri jurnal yang akan dibuat
     * @param string $noReferensi Nomor referensi untuk entri jurnal
     * @param string $keterangan Keterangan transaksi
     * @param string|null $tanggal Tanggal transaksi, default adalah hari ini
     * @return void
     * 
     * Contoh penggunaan:
     * $entries = [
     *    ['akun_id' => 1, 'debit' => 1000000, 'kredit' => 0],
     *    ['akun_id' => 2, 'debit' => 0, 'kredit' => 1000000]
     * ];
     * $this->createJournalEntries($entries, 'INV-001', 'Pembayaran Invoice INV-001');
     */
    public function createJournalEntries(array $entries, string $noReferensi, string $keterangan, ?string $tanggal = null)
    {
        // Validasi bahwa total debit dan kredit harus sama
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($entries as $entry) {
            $totalDebit += (float) ($entry['debit'] ?? 0);
            $totalKredit += (float) ($entry['kredit'] ?? 0);
        }

        if (abs($totalDebit - $totalKredit) > 0.01) {
            throw new \Exception('Total debit dan kredit harus sama untuk entri jurnal. Debit: ' . $totalDebit . ', Kredit: ' . $totalKredit);
        }

        try {
            DB::beginTransaction();

            $tanggal = $tanggal ?? date('Y-m-d');
            $userId = Auth::id() ?? 1; // Default ke admin jika tidak ada user yang login

            // Arrays untuk menyimpan perubahan saldo
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Buat entri jurnal untuk setiap akun yang terlibat
            foreach ($entries as $entry) {
                $newJurnal = JurnalUmum::create([
                    'tanggal' => $tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'] ?? 0,
                    'kredit' => $entry['kredit'] ?? 0,
                    'keterangan' => $keterangan,
                    'user_id' => $userId,
                    'ref_type' => get_class($this),
                    'ref_id' => $this->id
                ]);

                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::find($entry['akun_id']);

                if ($akun && $akun->ref_type) {
                    $debit = (float)($entry['debit'] ?? 0);
                    $kredit = (float)($entry['kredit'] ?? 0);

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Untuk akun Kas: debit menambah saldo, kredit mengurangi saldo
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Untuk akun Rekening Bank: debit menambah saldo, kredit mengurangi saldo
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Update saldo kas dan buat transaksi kas
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas) {
                    Log::info('AutomaticJournal - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    Log::info('AutomaticJournal - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiKas::create([
                            'tanggal' => $tanggal,
                            'kas_id' => $kasId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $keterangan,
                            'no_bukti' => $noReferensi,
                            'related_id' => $this->id,
                            'related_type' => get_class($this),
                            'user_id' => $userId
                        ]);
                    }
                }
            }

            // Update saldo rekening bank dan buat transaksi bank
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    Log::info('AutomaticJournal - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    Log::info('AutomaticJournal - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiBank::create([
                            'tanggal' => $tanggal,
                            'rekening_id' => $rekeningId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $keterangan,
                            'no_referensi' => $noReferensi,
                            'related_id' => $this->id,
                            'related_type' => get_class($this),
                            'user_id' => $userId
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Dapatkan entri jurnal terkait dengan model ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function journalEntries()
    {
        return $this->morphMany(JurnalUmum::class, 'referensi', 'ref_type', 'ref_id');
    }

    /**
     * Hapus entri jurnal terkait dengan model ini dan reverse saldo kas/bank.
     *
     * @return void
     */
    public function deleteJournalEntries()
    {
        try {
            DB::beginTransaction();

            // Get journal entries yang akan dihapus untuk reverse saldo
            $journalEntries = $this->journalEntries()->get();

            // Arrays untuk menyimpan perubahan saldo (reverse dari yang ada)
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            foreach ($journalEntries as $journal) {
                $akun = AkunAkuntansi::find($journal->akun_id);

                if ($akun && $akun->ref_type) {
                    $debit = (float)$journal->debit;
                    $kredit = (float)$journal->kredit;

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Reverse: jika debit menambah, sekarang kurangi
                        $nilaiPerubahan = $kredit - $debit; // Kebalikan dari saat create

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Reverse: jika debit menambah, sekarang kurangi
                        $nilaiPerubahan = $kredit - $debit; // Kebalikan dari saat create

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Hapus journal entries
            $this->journalEntries()->delete();

            $userId = Auth::id() ?? 1;

            // Update saldo kas (reverse)
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas) {
                    Log::info('AutomaticJournal Delete - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    Log::info('AutomaticJournal Delete - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat reverse
                    if ($nilaiPerubahan != 0) {
                        TransaksiKas::create([
                            'tanggal' => date('Y-m-d'),
                            'kas_id' => $kasId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => 'Reverse: ' . get_class($this) . ' ID: ' . $this->id,
                            'no_bukti' => 'REV-' . get_class($this) . '-' . $this->id,
                            'related_id' => $this->id,
                            'related_type' => get_class($this),
                            'user_id' => $userId
                        ]);
                    }
                }
            }

            // Update saldo rekening bank (reverse)
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    Log::info('AutomaticJournal Delete - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    Log::info('AutomaticJournal Delete - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat reverse
                    if ($nilaiPerubahan != 0) {
                        TransaksiBank::create([
                            'tanggal' => date('Y-m-d'),
                            'rekening_id' => $rekeningId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => 'Reverse: ' . get_class($this) . ' ID: ' . $this->id,
                            'no_referensi' => 'REV-' . get_class($this) . '-' . $this->id,
                            'related_id' => $this->id,
                            'related_type' => get_class($this),
                            'user_id' => $userId
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
