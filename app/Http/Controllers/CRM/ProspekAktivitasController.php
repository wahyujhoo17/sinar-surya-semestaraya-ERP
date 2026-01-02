<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Prospek;
use App\Models\ProspekAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspekAktivitasController extends Controller
{
    /**
     * Check if current user can access all prospects (admin/manager_penjualan)
     */
    private function canAccessAllProspects()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userTimezone = Auth::user()->timezone ?? 'Asia/Jakarta';

        // Filter aktivitas dengan role-based access control
        $query = ProspekAktivitas::query()
            ->with(['prospek', 'customer', 'user'])
            ->orderBy('tanggal', 'desc');

        // Apply role-based access control
        if (!$this->canAccessAllProspects()) {
            $query->whereHas('prospek', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Filter by prospek
        if ($request->has('prospek_id') && $request->prospek_id) {
            $query->where('prospek_id', $request->prospek_id);
        }

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        // Filter by status
        if ($request->has('status_followup') && $request->status_followup) {
            $query->where('status_followup', $request->status_followup);
        }

        // Filter by user (pembuat aktivitas)
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortDir = $request->get('sort_dir', 'desc');

        // Validate sort fields to prevent SQL injection
        $allowedSortFields = [
            'tanggal',
            'prospek_id',
            'tipe',
            'judul',
            'tanggal_followup',
            'status_followup'
        ];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');

            // If sorting by anything other than tanggal, add tanggal as secondary sort
            if ($sortBy !== 'tanggal') {
                $query->orderBy('tanggal', 'desc');
            }
        }

        // Get aktivitas
        $aktivitas = $query->paginate(15)->withQueryString();

        return view('CRM.aktivitas.index', [
            'aktivitas' => $aktivitas,
            'tipeList' => ProspekAktivitas::getTipeList(),
            'statusList' => ProspekAktivitas::getStatusList(),
            'filter' => $request->all(),
            'userTimezone' => $userTimezone,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $prospek = null;
        if ($request->has('prospek_id') && $request->prospek_id) {
            // Role-based access control untuk prospek
            if ($this->canAccessAllProspects()) {
                $prospek = Prospek::findOrFail($request->prospek_id);
            } else {
                $prospek = Prospek::where('user_id', Auth::id())->findOrFail($request->prospek_id);
            }
        }

        // Filter prospek list berdasarkan role
        if ($this->canAccessAllProspects()) {
            $prospekList = Prospek::orderBy('nama_prospek')->get();
            $customerList = \App\Models\Customer::where('is_active', true)->orderBy('nama')->get();
        } else {
            $prospekList = Prospek::where('user_id', Auth::id())->orderBy('nama_prospek')->get();
            $customerList = \App\Models\Customer::where('is_active', true)
                ->where('sales_id', Auth::id())
                ->orderBy('nama')->get();
        }

        return view('CRM.aktivitas.create', [
            'prospek' => $prospek,
            'prospekList' => $prospekList,
            'customerList' => $customerList,
            'tipeList' => ProspekAktivitas::getTipeList(),
            'statusList' => ProspekAktivitas::getStatusList(),
            'userList' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:prospek,customer',
            'prospek_id' => 'required_if:entity_type,prospek|nullable|exists:prospek,id',
            'customer_id' => 'required_if:entity_type,customer|nullable|exists:customer,id',
            'tipe' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'hasil' => 'nullable|string',
            'perlu_followup' => 'nullable|boolean',
            'tanggal_followup' => 'nullable|date|required_if:perlu_followup,1',
            'status_followup' => 'nullable|string|required_if:perlu_followup,1',
            'catatan_followup' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // Max 10MB per file
        ]);

        // Validasi akses berdasarkan role
        if (!$this->canAccessAllProspects()) {
            if ($request->entity_type === 'prospek' && $request->prospek_id) {
                $prospekExists = Prospek::where('id', $request->prospek_id)
                    ->where('user_id', Auth::id())
                    ->exists();

                if (!$prospekExists) {
                    abort(403, 'Anda tidak memiliki akses ke prospek ini.');
                }
            } elseif ($request->entity_type === 'customer' && $request->customer_id) {
                $customerExists = \App\Models\Customer::where('id', $request->customer_id)
                    ->where('sales_id', Auth::id())
                    ->exists();

                if (!$customerExists) {
                    abort(403, 'Anda tidak memiliki akses ke customer ini.');
                }
            }
        }

        $aktivitas = new ProspekAktivitas();
        $aktivitas->prospek_id = $request->entity_type === 'prospek' ? $request->prospek_id : null;
        $aktivitas->customer_id = $request->entity_type === 'customer' ? $request->customer_id : null;
        $aktivitas->user_id = Auth::id();
        $aktivitas->tipe = $request->tipe;
        $aktivitas->judul = $request->judul;
        $aktivitas->deskripsi = $request->deskripsi;
        $aktivitas->tanggal = $request->tanggal;
        $aktivitas->hasil = $request->hasil;
        $aktivitas->perlu_followup = $request->has('perlu_followup') ? 1 : 0;

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('aktivitas_attachments', $filename, 'public');

                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
            $aktivitas->attachments = $attachments;
        }

        if ($aktivitas->perlu_followup) {
            $aktivitas->tanggal_followup = $request->tanggal_followup;
            $aktivitas->status_followup = $request->status_followup;
            $aktivitas->catatan_followup = $request->catatan_followup;

            // Update prospek's next follow-up date if needed
            $prospek = Prospek::find($request->prospek_id);
            if (!$prospek->tanggal_followup || $prospek->tanggal_followup > $aktivitas->tanggal_followup) {
                $prospek->tanggal_followup = $aktivitas->tanggal_followup;
                $prospek->save();
            }
        }

        $aktivitas->save();

        return redirect()
            ->route('crm.aktivitas.show', $aktivitas->id)
            ->with('success', 'Aktivitas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProspekAktivitas $aktivita)
    {
        // Role-based access control untuk show
        if (!$this->canAccessAllProspects()) {
            $aktivita->load(['prospek']);
            if ($aktivita->prospek->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        return view('CRM.aktivitas.show', [
            'aktivitas' => $aktivita->load(['prospek', 'user']),
            'tipeList' => ProspekAktivitas::getTipeList(),
            'statusList' => ProspekAktivitas::getStatusList(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProspekAktivitas $aktivita)
    {
        // Role-based access control untuk edit
        if (!$this->canAccessAllProspects()) {
            $aktivita->load(['prospek']);
            if ($aktivita->prospek->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        // Filter prospek list berdasarkan role
        if ($this->canAccessAllProspects()) {
            $prospekList = Prospek::orderBy('nama_prospek')->get();
            $customerList = \App\Models\Customer::where('is_active', true)->orderBy('nama')->get();
        } else {
            $prospekList = Prospek::where('user_id', Auth::id())->orderBy('nama_prospek')->get();
            $customerList = \App\Models\Customer::where('is_active', true)
                ->where('sales_id', Auth::id())
                ->orderBy('nama')->get();
        }

        return view('CRM.aktivitas.edit', [
            'aktivitas' => $aktivita,
            'prospekList' => $prospekList,
            'customerList' => $customerList,
            'tipeList' => ProspekAktivitas::getTipeList(),
            'statusList' => ProspekAktivitas::getStatusList(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProspekAktivitas $aktivita)
    {
        // Role-based access control untuk update
        if (!$this->canAccessAllProspects()) {
            $aktivita->load(['prospek']);
            if ($aktivita->prospek->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        $request->validate([
            'prospek_id' => 'required|exists:prospek,id',
            'tipe' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'hasil' => 'nullable|string',
            'perlu_followup' => 'nullable|boolean',
            'tanggal_followup' => 'nullable|date|required_if:perlu_followup,1',
            'status_followup' => 'nullable|string|required_if:perlu_followup,1',
            'catatan_followup' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // Max 10MB per file
        ]);

        // Validasi akses ke prospek yang akan diubah berdasarkan role
        if (!$this->canAccessAllProspects()) {
            $prospekExists = Prospek::where('id', $request->prospek_id)
                ->where('user_id', Auth::id())
                ->exists();

            if (!$prospekExists) {
                abort(403, 'Anda tidak memiliki akses ke prospek ini.');
            }
        }

        $aktivita->prospek_id = $request->prospek_id;
        $aktivita->tipe = $request->tipe;
        $aktivita->judul = $request->judul;
        $aktivita->deskripsi = $request->deskripsi;
        $aktivita->tanggal = $request->tanggal;
        $aktivita->hasil = $request->hasil;
        $aktivita->perlu_followup = $request->has('perlu_followup') ? 1 : 0;

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $existingAttachments = $aktivita->attachments ?? [];
            $newAttachments = [];

            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('aktivitas_attachments', $filename, 'public');

                $newAttachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }

            $aktivita->attachments = array_merge($existingAttachments, $newAttachments);
        }

        if ($aktivita->perlu_followup) {
            $aktivita->tanggal_followup = $request->tanggal_followup;
            $aktivita->status_followup = $request->status_followup;
            $aktivita->catatan_followup = $request->catatan_followup;

            // Update prospek's next follow-up date if this is the earliest
            $prospek = Prospek::find($request->prospek_id);

            // Get the earliest follow-up date for this prospect
            $earliestFollowup = ProspekAktivitas::where('prospek_id', $prospek->id)
                ->where('perlu_followup', 1)
                ->where('status_followup', '!=', ProspekAktivitas::STATUS_SELESAI)
                ->where('status_followup', '!=', ProspekAktivitas::STATUS_DIBATALKAN)
                ->orderBy('tanggal_followup')
                ->first();

            if ($earliestFollowup) {
                $prospek->tanggal_followup = $earliestFollowup->tanggal_followup;
                $prospek->save();
            }
        } else {
            $aktivita->tanggal_followup = null;
            $aktivita->status_followup = null;
            $aktivita->catatan_followup = null;
        }

        $aktivita->save();

        return redirect()
            ->route('crm.aktivitas.show', $aktivita->id)
            ->with('success', 'Aktivitas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProspekAktivitas $aktivita)
    {
        // Role-based access control untuk destroy
        if (!$this->canAccessAllProspects()) {
            $aktivita->load(['prospek']);
            if ($aktivita->prospek->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        $prospekId = $aktivita->prospek_id;
        $aktivita->delete();

        // Update the prospect's follow-up date
        $prospek = Prospek::find($prospekId);
        $earliestFollowup = ProspekAktivitas::where('prospek_id', $prospekId)
            ->where('perlu_followup', 1)
            ->where('status_followup', '!=', ProspekAktivitas::STATUS_SELESAI)
            ->where('status_followup', '!=', ProspekAktivitas::STATUS_DIBATALKAN)
            ->orderBy('tanggal_followup')
            ->first();

        if ($earliestFollowup) {
            $prospek->tanggal_followup = $earliestFollowup->tanggal_followup;
        } else {
            $prospek->tanggal_followup = null;
        }

        $prospek->save();

        return redirect()
            ->route('crm.aktivitas.index')
            ->with('success', 'Aktivitas berhasil dihapus');
    }

    /**
     * Update follow-up status.
     */
    public function updateFollowupStatus(Request $request, ProspekAktivitas $aktivita)
    {
        // Role-based access control untuk update followup status
        if (!$this->canAccessAllProspects()) {
            $aktivita->load(['prospek']);
            if ($aktivita->prospek->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
            }
        }

        $request->validate([
            'status_followup' => 'required|string',
            'catatan_followup' => 'nullable|string',
        ]);

        $aktivita->status_followup = $request->status_followup;
        if ($request->has('catatan_followup')) {
            $aktivita->catatan_followup = $request->catatan_followup;
        }
        $aktivita->save();

        // If status is completed, update the prospect's follow-up date
        if (
            $request->status_followup == ProspekAktivitas::STATUS_SELESAI ||
            $request->status_followup == ProspekAktivitas::STATUS_DIBATALKAN
        ) {

            $prospek = Prospek::find($aktivita->prospek_id);
            $earliestFollowup = ProspekAktivitas::where('prospek_id', $aktivita->prospek_id)
                ->where('perlu_followup', 1)
                ->where('status_followup', '!=', ProspekAktivitas::STATUS_SELESAI)
                ->where('status_followup', '!=', ProspekAktivitas::STATUS_DIBATALKAN)
                ->orderBy('tanggal_followup')
                ->first();

            if ($earliestFollowup) {
                $prospek->tanggal_followup = $earliestFollowup->tanggal_followup;
            } else {
                $prospek->tanggal_followup = null;
            }

            $prospek->save();
        }

        return redirect()->back()->with('success', 'Status follow-up berhasil diperbarui');
    }

    /**
     * Get follow-ups that need attention (coming up or overdue).
     */
    public function followups(Request $request)
    {
        $userTimezone = Auth::user()->timezone ?? 'Asia/Jakarta';

        // Filter aktivitas dengan role-based access control
        $query = ProspekAktivitas::query()
            ->with(['prospek', 'user'])
            ->where('perlu_followup', 1)
            ->whereIn('status_followup', [null, ProspekAktivitas::STATUS_MENUNGGU])
            ->orderBy('tanggal_followup');

        // Apply role-based access control
        if (!$this->canAccessAllProspects()) {
            $query->whereHas('prospek', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal_followup', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal_followup', '<=', $request->end_date);
        }

        // Get followups
        $followups = $query->paginate(15);

        // Count by status for summary
        $baseQuery = ProspekAktivitas::query()
            ->where('perlu_followup', 1);

        // Apply same role-based access control for counts
        if (!$this->canAccessAllProspects()) {
            $baseQuery->whereHas('prospek', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Apply same filters for counts
        if ($request->has('user_id') && $request->user_id) {
            $baseQuery->where('user_id', $request->user_id);
        }
        if ($request->has('tipe') && $request->tipe) {
            $baseQuery->where('tipe', $request->tipe);
        }
        if ($request->has('start_date') && $request->start_date) {
            $baseQuery->whereDate('tanggal_followup', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $baseQuery->whereDate('tanggal_followup', '<=', $request->end_date);
        }

        $menunggu_count = (clone $baseQuery)->whereIn('status_followup', [null, ProspekAktivitas::STATUS_MENUNGGU])->count();
        $selesai_count = (clone $baseQuery)->where('status_followup', ProspekAktivitas::STATUS_SELESAI)->count();

        return view('CRM.aktivitas.followups', [
            'followups' => $followups,
            'tipeList' => ProspekAktivitas::getTipeList(),
            'statusList' => ProspekAktivitas::getStatusList(),
            'userList' => User::where('is_active', true)->orderBy('name')->get(),
            'filter' => $request->all(),
            'userTimezone' => $userTimezone,
            'menunggu_count' => $menunggu_count,
            'selesai_count' => $selesai_count,
        ]);
    }

    /**
     * Process batch update for follow-up status.
     */
    public function batchUpdateFollowup(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:prospek_aktivitas,id',
            'status_followup' => 'required|string',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $aktivitas = ProspekAktivitas::with('prospek')->find($id);
            if ($aktivitas && $aktivitas->perlu_followup) {
                // Role-based access control untuk batch update
                if (!$this->canAccessAllProspects()) {
                    if ($aktivitas->prospek->user_id !== Auth::id()) {
                        continue; // Skip aktivitas yang tidak dapat diakses
                    }
                }

                $aktivitas->status_followup = $request->status_followup;
                $aktivitas->save();
                $count++;

                // Update prospek follow-up date if needed
                if (
                    $request->status_followup == ProspekAktivitas::STATUS_SELESAI ||
                    $request->status_followup == ProspekAktivitas::STATUS_DIBATALKAN
                ) {
                    $this->updateProspekFollowupDate($aktivitas->prospek_id);
                }
            }
        }

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "$count follow-up berhasil diperbarui"
        ]);
    }

    /**
     * Process batch delete for activities.
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:prospek_aktivitas,id',
        ]);

        $count = 0;
        $prospekIds = [];

        foreach ($request->ids as $id) {
            $aktivitas = ProspekAktivitas::with('prospek')->find($id);
            if ($aktivitas) {
                // Role-based access control untuk batch delete
                if (!$this->canAccessAllProspects()) {
                    if ($aktivitas->prospek->user_id !== Auth::id()) {
                        continue; // Skip aktivitas yang tidak dapat diakses
                    }
                }

                $prospekIds[] = $aktivitas->prospek_id;
                $aktivitas->delete();
                $count++;
            }
        }

        // Update prospek follow-up dates
        $prospekIds = array_unique($prospekIds);
        foreach ($prospekIds as $prospekId) {
            $this->updateProspekFollowupDate($prospekId);
        }

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "$count aktivitas berhasil dihapus"
        ]);
    }

    /**
     * Helper method to update the prospek follow-up date.
     */
    private function updateProspekFollowupDate($prospekId)
    {
        $prospek = Prospek::find($prospekId);
        if (!$prospek) return;

        $earliestFollowup = ProspekAktivitas::where('prospek_id', $prospekId)
            ->where('perlu_followup', 1)
            ->where('status_followup', '!=', ProspekAktivitas::STATUS_SELESAI)
            ->where('status_followup', '!=', ProspekAktivitas::STATUS_DIBATALKAN)
            ->orderBy('tanggal_followup')
            ->first();

        if ($earliestFollowup) {
            $prospek->tanggal_followup = $earliestFollowup->tanggal_followup;
        } else {
            $prospek->tanggal_followup = null;
        }

        $prospek->save();
    }

    /**
     * Download attachment file
     */
    public function downloadAttachment($id, $attachmentIndex)
    {
        $aktivitas = ProspekAktivitas::with('prospek')->findOrFail($id);

        // Check if user can view this activity
        if (!$this->canAccessAllProspects() && $aktivitas->prospek->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        $attachments = $aktivitas->attachments ?? [];

        if (!isset($attachments[$attachmentIndex])) {
            abort(404, 'File tidak ditemukan.');
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->download($filePath, $attachment['original_name']);
    }

    /**
     * Delete specific attachment
     */
    public function deleteAttachment($id, $attachmentIndex)
    {
        $aktivitas = ProspekAktivitas::with('prospek')->findOrFail($id);

        // Check if user can edit this activity
        if (!$this->canAccessAllProspects() && $aktivitas->prospek->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file ini.'
            ], 403);
        }

        $attachments = $aktivitas->attachments ?? [];

        if (!isset($attachments[$attachmentIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }

        // Delete file from storage
        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove from array and update
        unset($attachments[$attachmentIndex]);
        $aktivitas->attachments = array_values($attachments); // Re-index array
        $aktivitas->save();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus.'
        ]);
    }
}
