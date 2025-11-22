<?php

namespace App\Http\Controllers;

use App\Models\AccountingConfiguration;
use App\Models\AkunAkuntansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configurations = AccountingConfiguration::with('akun')
            ->orderBy('transaction_type')
            ->orderBy('account_key')
            ->get()
            ->groupBy('transaction_type');

        $akunList = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        return view('keuangan.accounting-configurations.index', compact('configurations', 'akunList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akunList = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        return view('accounting-configuration.create', compact('akunList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|string',
            'account_key' => 'required|string',
            'account_name' => 'required|string',
            'akun_id' => 'nullable|exists:akun_akuntansi,id',
            'is_required' => 'boolean',
            'description' => 'nullable|string'
        ]);

        AccountingConfiguration::create($request->all());

        return redirect()->route('accounting-configuration.index')
            ->with('success', 'Konfigurasi accounting berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $configuration = AccountingConfiguration::with('akun')->findOrFail($id);

        return view('accounting-configuration.show', compact('configuration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $configuration = AccountingConfiguration::findOrFail($id);
        $akunList = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        return view('accounting-configuration.edit', compact('configuration', 'akunList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $configuration = AccountingConfiguration::findOrFail($id);

        $request->validate([
            'akun_id' => 'nullable|exists:akun_akuntansi,id',
            'description' => 'nullable|string'
        ]);

        $configuration->update([
            'akun_id' => $request->akun_id,
            'description' => $request->description
        ]);

        return redirect()->route('accounting-configuration.index')
            ->with('success', 'Konfigurasi accounting berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $configuration = AccountingConfiguration::findOrFail($id);

        // Cek apakah konfigurasi required
        if ($configuration->is_required) {
            return redirect()->route('accounting-configuration.index')
                ->with('error', 'Konfigurasi yang diperlukan tidak dapat dihapus');
        }

        $configuration->delete();

        return redirect()->route('accounting-configuration.index')
            ->with('success', 'Konfigurasi accounting berhasil dihapus');
    }

    /**
     * Bulk update configurations
     */
    public function bulkUpdate(Request $request)
    {
        $updates = $request->input('configurations', []);

        // Validate that at least one configuration is provided
        if (empty($updates)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada konfigurasi yang perlu diperbarui'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tidak ada konfigurasi yang perlu diperbarui');
        }

        DB::beginTransaction();
        try {
            $updatedCount = 0;
            foreach ($updates as $id => $data) {
                $config = AccountingConfiguration::find($id);
                if ($config) {
                    $config->update([
                        'akun_id' => $data['akun_id'] ?? null
                    ]);
                    $updatedCount++;
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil memperbarui {$updatedCount} konfigurasi akun",
                    'updated_count' => $updatedCount
                ]);
            }

            return redirect()->route('keuangan.accounting-configuration.index')
                ->with('success', "Kalibrasi berhasil disimpan! {$updatedCount} konfigurasi akun telah diperbarui.");
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui konfigurasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui konfigurasi: ' . $e->getMessage());
        }
    }
}
