<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\UangMukaPenjualan;
use App\Models\UangMukaAplikasi;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\Invoice;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\LogAktivitas;
use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class UangMukaPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = UangMukaPenjualan::with(['customer', 'salesOrder', 'user', 'kas', 'rekeningBank']);

        // Get sort column and direction
        $sortColumn = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = [
            'nomor',
            'tanggal',
            'customer_id',
            'jumlah',
            'jumlah_tersedia',
            'status'
        ];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'tanggal';
        }

        // Apply sorting
        if ($sortColumn === 'customer_id') {
            // Sort by customer name if sorting by customer
            $query->join('customer', 'uang_muka_penjualan.customer_id', '=', 'customer.id')
                ->orderBy(DB::raw('COALESCE(customer.company, customer.nama)'), $sortDirection)
                ->select('uang_muka_penjualan.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Filter berdasarkan customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('nomor_referensi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q_customer) use ($search) {
                        $q_customer->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        $uangMukaPenjualan = $query->paginate(10)->withQueryString();

        $customers = Customer::orderBy('nama')->get();

        return view('keuangan.uang-muka-penjualan.index', compact(
            'uangMukaPenjualan',
            'customers',
            'sortColumn',
            'sortDirection'
        ));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama')->get();
        $salesOrders = SalesOrder::whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
            ->with('customer')
            ->orderBy('tanggal', 'desc')
            ->get();
        $kasAccounts = Kas::where('is_aktif', true)->orderBy('nama')->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->orderBy('nama_bank')->get();
        $nomor = UangMukaPenjualan::generateNomor();

        return view('keuangan.uang-muka-penjualan.create', compact(
            'customers',
            'salesOrders',
            'kasAccounts',
            'bankAccounts',
            'nomor'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nomor' => 'required|string|unique:uang_muka_penjualan,nomor',
                'tanggal' => 'required|date',
                'customer_id' => 'required|exists:customer,id',
                'sales_order_id' => 'nullable|exists:sales_order,id',
                'jumlah' => 'required|numeric|min:0.01',
                'metode_pembayaran' => 'required|in:kas,bank',
                'kas_id' => 'required_if:metode_pembayaran,kas|nullable|exists:kas,id',
                'rekening_bank_id' => 'required_if:metode_pembayaran,bank|nullable|exists:rekening_bank,id',
                'nomor_referensi' => 'nullable|string',
                'keterangan' => 'nullable|string',
            ]);

            Log::info('Validation passed', $validatedData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Buat uang muka penjualan
            $uangMuka = new UangMukaPenjualan();
            $uangMuka->nomor = $request->nomor;
            $uangMuka->tanggal = $request->tanggal;
            $uangMuka->customer_id = $request->customer_id;
            $uangMuka->sales_order_id = $request->sales_order_id;
            $uangMuka->user_id = Auth::id();
            $uangMuka->jumlah = $request->jumlah;
            $uangMuka->jumlah_tersedia = $request->jumlah;
            $uangMuka->metode_pembayaran = $request->metode_pembayaran;
            $uangMuka->kas_id = $request->metode_pembayaran === 'kas' ? $request->kas_id : null;
            $uangMuka->rekening_bank_id = $request->metode_pembayaran === 'bank' ? $request->rekening_bank_id : null;
            $uangMuka->nomor_referensi = $request->nomor_referensi;
            $uangMuka->status = 'confirmed'; // Langsung confirmed
            $uangMuka->keterangan = $request->keterangan;
            $uangMuka->save();

            // Buat jurnal entry untuk penerimaan uang muka (langsung posted)
            $this->createJournalEntry($uangMuka);

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'create',
                'modul' => 'uang_muka_penjualan',
                'data_id' => $uangMuka->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $uangMuka->nomor,
                    'customer' => $uangMuka->customer->nama ?? $uangMuka->customer->company,
                    'jumlah' => $uangMuka->jumlah,
                    'metode_pembayaran' => $uangMuka->metode_pembayaran,
                ])
            ]);

            DB::commit();

            Log::info('Uang muka penjualan created successfully', [
                'id' => $uangMuka->id,
                'nomor' => $uangMuka->nomor,
                'customer_id' => $uangMuka->customer_id,
                'jumlah' => $uangMuka->jumlah
            ]);

            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('success', 'Uang muka penjualan berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating uang muka penjualan: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mencatat uang muka. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $uangMuka = UangMukaPenjualan::with([
            'customer',
            'salesOrder',
            'user',
            'kas',
            'rekeningBank',
            'aplikasi.invoice'
        ])->findOrFail($id);

        return view('keuangan.uang-muka-penjualan.show', compact('uangMuka'));
    }

    public function edit($id)
    {
        $uangMuka = UangMukaPenjualan::findOrFail($id);

        // Cek apakah sudah ada aplikasi
        if ($uangMuka->aplikasi()->exists()) {
            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('error', 'Uang muka ini tidak dapat diedit karena sudah diaplikasikan ke invoice.');
        }

        $customers = Customer::orderBy('nama')->get();
        $salesOrders = SalesOrder::whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
            ->with('customer')
            ->orderBy('tanggal', 'desc')
            ->get();
        $kasAccounts = Kas::where('is_aktif', true)->orderBy('nama')->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->orderBy('nama_bank')->get();

        return view('keuangan.uang-muka-penjualan.edit', compact(
            'uangMuka',
            'customers',
            'salesOrders',
            'kasAccounts',
            'bankAccounts'
        ));
    }

    public function update(Request $request, $id)
    {
        $uangMuka = UangMukaPenjualan::findOrFail($id);

        // Cek apakah sudah ada aplikasi
        if ($uangMuka->aplikasi()->exists()) {
            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('error', 'Uang muka ini tidak dapat diupdate karena sudah diaplikasikan ke invoice.');
        }

        try {
            $validatedData = $request->validate([
                'nomor' => 'required|string|unique:uang_muka_penjualan,nomor,' . $id,
                'tanggal' => 'required|date',
                'customer_id' => 'required|exists:customer,id',
                'sales_order_id' => 'nullable|exists:sales_order,id',
                'jumlah' => 'required|numeric|min:0.01',
                'metode_pembayaran' => 'required|in:kas,bank',
                'kas_id' => 'required_if:metode_pembayaran,kas|nullable|exists:kas,id',
                'rekening_bank_id' => 'required_if:metode_pembayaran,bank|nullable|exists:rekening_bank,id',
                'nomor_referensi' => 'nullable|string|max:100',
                'keterangan' => 'nullable|string|max:500',
            ]);

            Log::info('Update validation passed', $validatedData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update validation failed', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Simpan data lama untuk rollback saldo dan jurnal
            $oldJumlah = $uangMuka->jumlah;
            $oldMetode = $uangMuka->metode_pembayaran;
            $oldKasId = $uangMuka->kas_id;
            $oldBankId = $uangMuka->rekening_bank_id;

            // Hitung jumlah yang sudah diaplikasikan
            $jumlahTeraplikasi = $uangMuka->aplikasi()->sum('jumlah_aplikasi');

            // Validasi: jumlah baru tidak boleh lebih kecil dari yang sudah diaplikasikan
            if ($request->jumlah < $jumlahTeraplikasi) {
                throw new \Exception('Jumlah baru tidak boleh lebih kecil dari yang sudah diaplikasikan (Rp ' . number_format($jumlahTeraplikasi, 0, ',', '.') . ')');
            }

            // Validasi kas/bank aktif dan saldo mencukupi
            if ($request->metode_pembayaran === 'kas') {
                $kas = Kas::where('id', $request->kas_id)->where('is_aktif', true)->first();
                if (!$kas) {
                    throw new \Exception('Kas yang dipilih tidak aktif atau tidak ditemukan');
                }

                // Cek saldo untuk rollback dan penambahan baru
                $saldoSetelahRollback = $kas->saldo + ($oldMetode === 'kas' && $oldKasId == $kas->id ? -$oldJumlah : 0);
                if ($saldoSetelahRollback + $request->jumlah < 0) {
                    throw new \Exception('Saldo kas tidak mencukupi untuk perubahan ini');
                }
            } else {
                $bank = RekeningBank::where('id', $request->rekening_bank_id)->where('is_aktif', true)->first();
                if (!$bank) {
                    throw new \Exception('Rekening bank yang dipilih tidak aktif atau tidak ditemukan');
                }

                // Cek saldo untuk rollback dan penambahan baru
                $saldoSetelahRollback = $bank->saldo + ($oldMetode === 'bank' && $oldBankId == $bank->id ? -$oldJumlah : 0);
                if ($saldoSetelahRollback + $request->jumlah < 0) {
                    throw new \Exception('Saldo bank tidak mencukupi untuk perubahan ini');
                }
            }

            // Hapus jurnal entry lama
            JurnalUmum::where('ref_type', 'App\\Models\\UangMukaPenjualan')
                ->where('ref_id', $uangMuka->id)
                ->where('sumber', 'uang_muka_penjualan')
                ->delete();

            // Rollback saldo lama
            if ($oldMetode === 'kas' && $oldKasId) {
                $kas = Kas::find($oldKasId);
                if ($kas) {
                    $kas->saldo -= $oldJumlah;
                    $kas->save();
                }
            } elseif ($oldMetode === 'bank' && $oldBankId) {
                $bank = RekeningBank::find($oldBankId);
                if ($bank) {
                    $bank->saldo -= $oldJumlah;
                    $bank->save();
                }
            }

            // Update uang muka
            $uangMuka->nomor = $request->nomor;
            $uangMuka->tanggal = $request->tanggal;
            $uangMuka->customer_id = $request->customer_id;
            $uangMuka->sales_order_id = $request->sales_order_id;
            $uangMuka->jumlah = $request->jumlah;
            $uangMuka->jumlah_tersedia = $request->jumlah - $jumlahTeraplikasi; // Hitung ulang sisa tersedia
            $uangMuka->metode_pembayaran = $request->metode_pembayaran;
            $uangMuka->kas_id = $request->metode_pembayaran === 'kas' ? $request->kas_id : null;
            $uangMuka->rekening_bank_id = $request->metode_pembayaran === 'bank' ? $request->rekening_bank_id : null;
            $uangMuka->nomor_referensi = $request->nomor_referensi;
            $uangMuka->keterangan = $request->keterangan;
            $uangMuka->save();

            // Update saldo baru
            if ($request->metode_pembayaran === 'kas') {
                $kas = Kas::find($request->kas_id);
                $kas->saldo += $request->jumlah;
                $kas->save();
            } else {
                $bank = RekeningBank::find($request->rekening_bank_id);
                $bank->saldo += $request->jumlah;
                $bank->save();
            }

            // Buat jurnal entry baru
            $this->createJournalEntry($uangMuka);

            // Update status berdasarkan aplikasi
            $uangMuka->updateStatus();

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'update',
                'modul' => 'uang_muka_penjualan',
                'data_id' => $uangMuka->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $uangMuka->nomor,
                    'customer' => $uangMuka->customer->nama ?? $uangMuka->customer->company,
                    'jumlah_lama' => $oldJumlah,
                    'jumlah_baru' => $uangMuka->jumlah,
                    'metode_lama' => $oldMetode,
                    'metode_baru' => $uangMuka->metode_pembayaran,
                ])
            ]);

            DB::commit();

            Log::info('Uang muka penjualan updated successfully', [
                'id' => $uangMuka->id,
                'nomor' => $uangMuka->nomor,
                'old_amount' => $oldJumlah,
                'new_amount' => $uangMuka->jumlah
            ]);

            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('success', 'Uang muka penjualan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating uang muka penjualan: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate uang muka. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $uangMuka = UangMukaPenjualan::findOrFail($id);

            // Cek apakah sudah ada aplikasi
            if ($uangMuka->aplikasi()->exists()) {
                return redirect()->route('keuangan.uang-muka-penjualan.index')
                    ->with('error', 'Uang muka ini tidak dapat dihapus karena sudah diaplikasikan ke invoice.');
            }

            DB::beginTransaction();

            // Rollback saldo
            if ($uangMuka->metode_pembayaran === 'kas' && $uangMuka->kas_id) {
                $kas = Kas::find($uangMuka->kas_id);
                if ($kas) {
                    $kas->saldo -= $uangMuka->jumlah;
                    $kas->save();
                }
            } elseif ($uangMuka->metode_pembayaran === 'bank' && $uangMuka->rekening_bank_id) {
                $bank = RekeningBank::find($uangMuka->rekening_bank_id);
                if ($bank) {
                    $bank->saldo -= $uangMuka->jumlah;
                    $bank->save();
                }
            }

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'modul' => 'uang_muka_penjualan',
                'data_id' => $uangMuka->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $uangMuka->nomor,
                    'customer' => $uangMuka->customer->nama ?? $uangMuka->customer->company,
                    'jumlah' => $uangMuka->jumlah,
                ])
            ]);

            $uangMuka->delete();

            DB::commit();

            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('success', 'Uang muka penjualan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting uang muka penjualan: ' . $e->getMessage());

            return redirect()->route('keuangan.uang-muka-penjualan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus uang muka. Error: ' . $e->getMessage());
        }
    }

    /**
     * Aplikasikan uang muka ke invoice
     */
    public function applyToInvoice(Request $request, $id)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoice,id',
            'jumlah_aplikasi' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $uangMuka = UangMukaPenjualan::findOrFail($id);
            $invoice = Invoice::findOrFail($request->invoice_id);

            // Validasi
            if ($uangMuka->customer_id !== $invoice->customer_id) {
                return response()->json(['error' => 'Customer tidak sama'], 400);
            }

            if ($request->jumlah_aplikasi > $uangMuka->jumlah_tersedia) {
                return response()->json(['error' => 'Jumlah melebihi saldo tersedia'], 400);
            }

            // Buat aplikasi
            $aplikasi = new UangMukaAplikasi();
            $aplikasi->uang_muka_penjualan_id = $uangMuka->id;
            $aplikasi->invoice_id = $invoice->id;
            $aplikasi->jumlah_aplikasi = $request->jumlah_aplikasi;
            $aplikasi->tanggal_aplikasi = \Carbon\Carbon::now();
            $aplikasi->keterangan = $request->keterangan ?? 'Aplikasi uang muka ke invoice ' . $invoice->nomor;
            $aplikasi->save();

            // Update uang muka
            $uangMuka->updateStatus();

            // Update invoice - hitung sisa dengan logika: (Subtotal - UM) + PPN 11%
            $invoice->uang_muka_terapkan += $request->jumlah_aplikasi;

            // Hitung sisa tagihan dengan logika baru
            $subtotalItems = $invoice->subtotal ?? 0;
            $sisaSetelahUM = $subtotalItems - $invoice->uang_muka_terapkan;
            $ppnAmount = $sisaSetelahUM * 0.11;
            $invoice->sisa_tagihan = $sisaSetelahUM + $ppnAmount;

            $invoice->save();

            // Buat jurnal entry untuk aplikasi uang muka
            $this->createApplicationJournalEntry($aplikasi);

            DB::commit();

            return response()->json(['success' => 'Uang muka berhasil diaplikasikan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Buat jurnal entry untuk penerimaan uang muka
     */
    private function createJournalEntry($uangMuka)
    {
        // Cari akun
        $akunKas = null;
        $akunBank = null;
        $akunUangMukaPenjualan = AkunAkuntansi::where('kode', '2201')->first(); // Hutang Uang Muka Penjualan

        if ($uangMuka->metode_pembayaran === 'kas') {
            $akunKas = $uangMuka->kas->akunAkuntansi ?? AkunAkuntansi::where('kode', '1001')->first();
        } else {
            $akunBank = $uangMuka->rekeningBank->akunAkuntansi ?? AkunAkuntansi::where('kode', '1002')->first();
        }

        if (!$akunUangMukaPenjualan) {
            throw new \Exception('Akun Hutang Uang Muka Penjualan (2201) tidak ditemukan. Silakan hubungi administrator.');
        }

        $akunKasBankId = $uangMuka->metode_pembayaran === 'kas' ? $akunKas->id : $akunBank->id;

        // Buat jurnal debit kas/bank
        JurnalUmum::create([
            'tanggal' => $uangMuka->tanggal,
            'no_referensi' => 'DP-' . $uangMuka->nomor,
            'akun_id' => $akunKasBankId,
            'debit' => $uangMuka->jumlah,
            'kredit' => 0,
            'keterangan' => 'Penerimaan uang muka dari customer: ' . ($uangMuka->customer->company ?? $uangMuka->customer->nama),
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_penjualan',
            'ref_type' => 'App\\Models\\UangMukaPenjualan',
            'ref_id' => $uangMuka->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Jurnal otomatis langsung diposting
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);

        // Buat jurnal kredit hutang uang muka
        JurnalUmum::create([
            'tanggal' => $uangMuka->tanggal,
            'no_referensi' => 'DP-' . $uangMuka->nomor,
            'akun_id' => $akunUangMukaPenjualan->id,
            'debit' => 0,
            'kredit' => $uangMuka->jumlah,
            'keterangan' => 'Hutang uang muka penjualan dari customer: ' . ($uangMuka->customer->company ?? $uangMuka->customer->nama),
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_penjualan',
            'ref_type' => 'App\\Models\\UangMukaPenjualan',
            'ref_id' => $uangMuka->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Jurnal otomatis langsung diposting
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);

        // Update saldo kas/bank dan catat transaksi karena jurnal sudah posted
        if ($uangMuka->metode_pembayaran === 'kas') {
            $kas = Kas::find($uangMuka->kas_id);
            if ($kas) {
                $kas->saldo += $uangMuka->jumlah;
                $kas->save();

                // Catat transaksi kas untuk audit trail
                TransaksiKas::create([
                    'tanggal' => $uangMuka->tanggal,
                    'kas_id' => $uangMuka->kas_id,
                    'jenis' => 'masuk',
                    'jumlah' => $uangMuka->jumlah,
                    'keterangan' => 'Penerimaan uang muka penjualan - ' . ($uangMuka->customer->company ?? $uangMuka->customer->nama),
                    'no_bukti' => 'DP-' . $uangMuka->nomor,
                    'related_id' => $uangMuka->id,
                    'related_type' => 'App\\Models\\UangMukaPenjualan',
                    'user_id' => Auth::id()
                ]);
            }
        } else {
            $bank = RekeningBank::find($uangMuka->rekening_bank_id);
            if ($bank) {
                $bank->saldo += $uangMuka->jumlah;
                $bank->save();

                // Catat transaksi bank untuk audit trail
                TransaksiBank::create([
                    'tanggal' => $uangMuka->tanggal,
                    'rekening_id' => $uangMuka->rekening_bank_id,
                    'jenis' => 'masuk',
                    'jumlah' => $uangMuka->jumlah,
                    'keterangan' => 'Penerimaan uang muka penjualan - ' . ($uangMuka->customer->company ?? $uangMuka->customer->nama),
                    'no_referensi' => 'DP-' . $uangMuka->nomor,
                    'related_id' => $uangMuka->id,
                    'related_type' => 'App\\Models\\UangMukaPenjualan',
                    'user_id' => Auth::id()
                ]);
            }
        }
    }

    /**
     * Buat jurnal entry untuk aplikasi uang muka ke invoice
     */
    private function createApplicationJournalEntry($aplikasi)
    {
        // Cek apakah jurnal sudah ada untuk aplikasi ini
        $existingJournal = JurnalUmum::where('ref_type', 'App\\Models\\UangMukaAplikasi')
            ->where('ref_id', $aplikasi->id)
            ->where('sumber', 'uang_muka_aplikasi')
            ->exists();

        if ($existingJournal) {
            // Jurnal sudah ada, skip untuk menghindari duplikasi
            return;
        }

        $akunUangMukaPenjualan = AkunAkuntansi::where('kode', '2201')->first();
        $akunPiutang = AkunAkuntansi::where('id', env('AKUN_PIUTANG_USAHA_ID'))->first();

        if (!$akunUangMukaPenjualan) {
            throw new \Exception('Akun Hutang Uang Muka Penjualan (2201) tidak ditemukan. Silakan hubungi administrator.');
        }

        if (!$akunPiutang) {
            $piutangId = env('AKUN_PIUTANG_USAHA_ID');
            throw new \Exception("Akun Piutang Usaha (ID: {$piutangId}) tidak ditemukan. Pastikan AKUN_PIUTANG_USAHA_ID di .env sudah benar.");
        }

        // Buat jurnal debit hutang uang muka
        JurnalUmum::create([
            'tanggal' => $aplikasi->tanggal_aplikasi,
            'no_referensi' => 'APP-' . $aplikasi->uangMukaPenjualan->nomor . '-' . $aplikasi->invoice->nomor,
            'akun_id' => $akunUangMukaPenjualan->id,
            'debit' => $aplikasi->jumlah_aplikasi,
            'kredit' => 0,
            'keterangan' => 'Aplikasi uang muka ke invoice: ' . $aplikasi->invoice->nomor,
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_aplikasi',
            'ref_type' => 'App\Models\UangMukaAplikasi',
            'ref_id' => $aplikasi->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Aplikasi uang muka tidak mempengaruhi saldo kas/bank
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);

        // Buat jurnal kredit piutang usaha
        JurnalUmum::create([
            'tanggal' => $aplikasi->tanggal_aplikasi,
            'no_referensi' => 'APP-' . $aplikasi->uangMukaPenjualan->nomor . '-' . $aplikasi->invoice->nomor,
            'akun_id' => $akunPiutang->id,
            'debit' => 0,
            'kredit' => $aplikasi->jumlah_aplikasi,
            'keterangan' => 'Pengurangan piutang karena aplikasi uang muka',
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_aplikasi',
            'ref_type' => 'App\Models\UangMukaAplikasi',
            'ref_id' => $aplikasi->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Aplikasi uang muka tidak mempengaruhi saldo kas/bank
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);
    }

    /**
     * Export PDF
     */
    public function exportPdf($id)
    {
        $uangMuka = UangMukaPenjualan::with([
            'customer',
            'salesOrder',
            'user',
            'kas',
            'rekeningBank',
            'aplikasi.invoice'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('keuangan.uang-muka-penjualan.pdf', compact('uangMuka'));
        $pdf->setPaper('a4', 'portrait');

        // Clean filename dari karakter yang tidak diizinkan
        $filename = 'UangMuka-' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $uangMuka->nomor) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print preview PDF in browser
     */
    public function print($id)
    {
        $uangMuka = UangMukaPenjualan::with([
            'customer',
            'salesOrder',
            'user',
            'kas',
            'rekeningBank',
            'aplikasi.invoice'
        ])->findOrFail($id);

        return view('keuangan.uang-muka-penjualan.print', compact('uangMuka'));
    }

    /**
     * Get available uang muka by customer for AJAX
     */
    public function getByCustomer($customerId)
    {
        $uangMuka = UangMukaPenjualan::byCustomer($customerId)
            ->available()
            ->with('customer')
            ->get();

        return response()->json($uangMuka);
    }
}
