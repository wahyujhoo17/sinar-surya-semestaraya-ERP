<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\Kas;
use App\Models\RekeningBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class COAController extends Controller
{
    /**
     * Menampilkan halaman daftar akun (Chart of Accounts)
     */
    public function index()
    {
        // Mengambil data akun dengan relasi parent dan children
        $akunRootLevel = AkunAkuntansi::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('kode', 'asc')
                    ->with(['children' => function ($q) {
                        $q->orderBy('kode', 'asc');
                    }]);
            }])
            ->orderBy('kode', 'asc')
            ->get();

        // Breadcrumbs untuk navigasi
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Chart of Accounts (COA)', 'url' => null]
        ];

        return view('keuangan.COA.index', compact(
            'akunRootLevel',
            'breadcrumbs'
        ))->with('currentPage', 'Chart of Accounts (COA)');
    }

    /**
     * Menampilkan form untuk membuat akun baru
     */    public function create()
    {
        // Mengambil semua akun untuk digunakan sebagai parent
        $allAccounts = AkunAkuntansi::orderBy('kode', 'asc')->get();

        // Get available kas and rekening data
        $kasOptions = Kas::where('is_aktif', true)->get();
        $rekeningOptions = RekeningBank::where('is_aktif', true)->where('is_perusahaan', true)->get();

        // Kategori dan tipe akun yang tersedia
        $kategoriAkun = [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
            'income' => 'Pendapatan',
            'expense' => 'Beban',
            'purchase' => 'Pembelian',
            'other_income' => 'Pendapatan di Luar Usaha',
            'other_expense' => 'Biaya di Luar Usaha',
            'other' => 'Lainnya'
        ];

        $tipeAkun = [
            'header' => 'Header (Grup)',
            'detail' => 'Detail (Transaksi)',
        ];

        return view('keuangan.COA.create', compact(
            'allAccounts',
            'kategoriAkun',
            'tipeAkun',
            'kasOptions',
            'rekeningOptions'
        ));
    }

    /**
     * Menyimpan akun baru ke database
     */
    public function store(Request $request)
    {
        // Validasi data masukan
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:akun_akuntansi,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:asset,liability,equity,income,expense,purchase,other_income,other_expense,other',
            'tipe' => 'required|string|in:header,detail',
            'parent_id' => 'nullable|exists:akun_akuntansi,id',
            'is_active' => 'boolean',
            'reference_type' => 'nullable|string|in:kas,rekening',
            'reference_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Buat akun baru
            $akun = new AkunAkuntansi();
            $akun->kode = $request->kode;
            $akun->nama = $request->nama;
            $akun->kategori = $request->kategori;
            $akun->tipe = $request->tipe;
            $akun->parent_id = $request->parent_id ?: null;
            $akun->is_active = $request->has('is_active');

            // Handle reference to kas or rekening
            if ($request->reference_type === 'kas' && $request->reference_id) {
                $akun->ref_type = Kas::class;
                $akun->ref_id = $request->reference_id;
            } elseif ($request->reference_type === 'rekening' && $request->reference_id) {
                $akun->ref_type = RekeningBank::class;
                $akun->ref_id = $request->reference_id;
            }

            $akun->save();

            DB::commit();

            return redirect()->route('keuangan.coa.index')
                ->with('success', 'Akun berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail akun
     */    public function show($id)
    {
        $akun = AkunAkuntansi::with(['parent', 'children', 'jurnalEntries', 'reference'])->findOrFail($id);

        // Calculate total balance from journal entries
        $totalDebit = $akun->jurnalEntries->sum('debit');
        $totalKredit = $akun->jurnalEntries->sum('kredit');

        // Calculate balance based on account category
        $balance = 0;
        if (in_array($akun->kategori, ['asset', 'expense'])) {
            $balance = $totalDebit - $totalKredit;
        } else {
            $balance = $totalKredit - $totalDebit;
        }

        // Get reference information
        $referenceInfo = null;
        if ($akun->ref_id && $akun->ref_type) {
            if ($akun->ref_type === Kas::class) {
                $referenceInfo = [
                    'type' => 'Kas',
                    'data' => Kas::find($akun->ref_id)
                ];
            } elseif ($akun->ref_type === RekeningBank::class) {
                $referenceInfo = [
                    'type' => 'Rekening Bank',
                    'data' => RekeningBank::find($akun->ref_id)
                ];
            }
        }

        return view('keuangan.COA.show', compact(
            'akun',
            'totalDebit',
            'totalKredit',
            'balance',
            'referenceInfo'
        ));
    }

    /**
     * Menampilkan form untuk mengedit akun
     */
    public function edit($id)
    {
        $akun = AkunAkuntansi::findOrFail($id);
        $allAccounts = AkunAkuntansi::where('id', '!=', $id)
            ->orderBy('kode', 'asc')
            ->get();

        // Get available kas and rekening data
        $kasOptions = Kas::where('is_aktif', true)->get();
        $rekeningOptions = RekeningBank::where('is_aktif', true)->where('is_perusahaan', true)->get();

        // Kategori dan tipe akun yang tersedia
        $kategoriAkun = [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
            'income' => 'Pendapatan',
            'expense' => 'Beban',
            'purchase' => 'Pembelian',
            'other_income' => 'Pendapatan di Luar Usaha',
            'other_expense' => 'Biaya di Luar Usaha',
            'other' => 'Lainnya'
        ];

        $tipeAkun = [
            'header' => 'Header (Grup)',
            'detail' => 'Detail (Transaksi)',
        ];

        return view('keuangan.COA.edit', compact(
            'akun',
            'allAccounts',
            'kategoriAkun',
            'tipeAkun',
            'kasOptions',
            'rekeningOptions'
        ));
    }

    /**
     * Menyimpan perubahan akun ke database
     */
    public function update(Request $request, $id)
    {
        // Validasi data masukan
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:akun_akuntansi,kode,' . $id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:asset,liability,equity,income,expense,purchase,other_income,other_expense,other',
            'tipe' => 'required|string|in:header,detail',
            'parent_id' => 'nullable|exists:akun_akuntansi,id',
            'is_active' => 'boolean',
            'reference_type' => 'nullable|string|in:kas,rekening',
            'reference_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $akun = AkunAkuntansi::findOrFail($id);

            // Prevent setting parent to self or child
            if ($request->parent_id && $request->parent_id == $id) {
                return back()->with('error', 'Akun tidak dapat menjadi parent dari dirinya sendiri.')->withInput();
            }

            // Update akun
            $akun->kode = $request->kode;
            $akun->nama = $request->nama;
            $akun->kategori = $request->kategori;
            $akun->tipe = $request->tipe;
            $akun->parent_id = $request->parent_id ?: null;
            $akun->is_active = $request->has('is_active');

            // Handle reference to kas or rekening
            if ($request->reference_type === 'kas' && $request->reference_id) {
                $akun->ref_type = Kas::class;
                $akun->ref_id = $request->reference_id;
            } elseif ($request->reference_type === 'rekening' && $request->reference_id) {
                $akun->ref_type = RekeningBank::class;
                $akun->ref_id = $request->reference_id;
            } else {
                $akun->ref_type = null;
                $akun->ref_id = null;
            }

            $akun->save();

            DB::commit();

            return redirect()->route('keuangan.coa.index')
                ->with('success', 'Akun berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus akun dari database
     */
    public function destroy($id)
    {
        try {
            $akun = AkunAkuntansi::findOrFail($id);

            // Check if account has children
            if ($akun->children()->count() > 0) {
                return back()->with('error', 'Akun tidak dapat dihapus karena memiliki sub-akun.');
            }

            // Check if account has journal entries
            if ($akun->jurnalEntries()->count() > 0) {
                return back()->with('error', 'Akun tidak dapat dihapus karena sudah memiliki transaksi jurnal.');
            }

            $akun->delete();

            return redirect()->route('keuangan.coa.index')
                ->with('success', 'Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate kode akun otomatis
     */
    public function generateCode(Request $request)
    {
        $parentId = $request->input('parent_id');
        $kategori = $request->input('kategori', 'asset');

        if ($parentId) {
            $parent = AkunAkuntansi::find($parentId);
            if ($parent) {
                $lastChild = AkunAkuntansi::where('parent_id', $parentId)
                    ->orderBy('kode', 'desc')
                    ->first();

                if ($lastChild) {
                    $parts = explode('.', $lastChild->kode);
                    $lastPart = (int) end($parts);
                    $newLastPart = $lastPart + 1;
                    $newCode = implode('.', array_slice($parts, 0, count($parts) - 1)) . '.' . str_pad($newLastPart, 2, '0', STR_PAD_LEFT);
                } else {
                    $newCode = $parent->kode . '.01';
                }

                return response()->json([
                    'success' => true,
                    'code' => $newCode
                ]);
            }
        }

        // Generate root level code based on category
        $prefix = '';
        switch ($kategori) {
            case 'asset':
                $prefix = '1';
                break;
            case 'liability':
                $prefix = '2';
                break;
            case 'equity':
                $prefix = '3';
                break;
            case 'income':
                $prefix = '4';
                break;
            case 'expense':
                $prefix = '5';
                break;
            case 'purchase':
                $prefix = '6';
                break;
            case 'other_income':
                $prefix = '7';
                break;
            case 'other_expense':
                $prefix = '8';
                break;
            default:
                $prefix = '9';
                break;
        }

        $lastRootAccount = AkunAkuntansi::whereNull('parent_id')
            ->where('kode', 'like', $prefix . '%')
            ->orderBy('kode', 'desc')
            ->first();

        if ($lastRootAccount) {
            $currentCode = (int) $lastRootAccount->kode;
            $newCode = (string) ($currentCode + 1);
        } else {
            $newCode = $prefix . '000';
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}