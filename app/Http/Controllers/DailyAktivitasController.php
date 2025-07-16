<?php

namespace App\Http\Controllers;

use App\Models\DailyAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DailyAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get statistics
        $statistics = DailyAktivitas::getStatistics($user->id);

        if ($request->ajax()) {
            return $this->data($request);
        }

        return view('daily_aktivitas.index', compact('statistics'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();

        // Base query - user can see their own activities and activities assigned to them
        $query = DailyAktivitas::with(['user', 'assignedTo', 'assignedUsers'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->orWhereHas('assignedUsers', function ($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id);
                    });
            });

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('prioritas')) {
            $query->byPrioritas($request->prioritas);
        }

        if ($request->filled('tipe_aktivitas')) {
            $query->byTipe($request->tipe_aktivitas);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('date_from')) {
            $query->where('tanggal_mulai', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('tanggal_mulai', '<=', $request->date_to);
        }

        // Apply quick filters
        if ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case 'today':
                    $query->today();
                    break;
                case 'tomorrow':
                    $query->tomorrow();
                    break;
                case 'this_week':
                    $query->thisWeek();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'tanggal_mulai');
        $sortDirection = $request->get('direction', 'asc');

        $validSortFields = ['tanggal_mulai', 'deadline', 'prioritas', 'status', 'judul', 'created_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $aktivitas = $query->paginate(15);

        // Transform data to include assigned_users for frontend
        $transformedData = $aktivitas->items();
        foreach ($transformedData as $item) {
            // Add assigned_users array for frontend
            $item->assigned_users = $item->assignedUsers->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->pivot->role ?? 'assigned'
                ];
            })->toArray();
        }

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'pagination' => [
                'current_page' => $aktivitas->currentPage(),
                'last_page' => $aktivitas->lastPage(),
                'per_page' => $aktivitas->perPage(),
                'total' => $aktivitas->total(),
                'from' => $aktivitas->firstItem(),
                'to' => $aktivitas->lastItem(),
                'links' => $aktivitas->linkCollection()
            ]
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('daily_aktivitas.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_aktivitas' => 'required|in:' . implode(',', array_keys(DailyAktivitas::TIPE_AKTIVITAS)),
            'prioritas' => 'required|in:' . implode(',', array_keys(DailyAktivitas::PRIORITAS)),
            'status' => 'required|in:' . implode(',', array_keys(DailyAktivitas::STATUS)),
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deadline' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'peserta' => 'nullable|string',
            'estimasi_durasi' => 'nullable|numeric|min:0|max:999.99',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            'reminder_at' => 'nullable|date'
        ]);

        $aktivitas = new DailyAktivitas();
        $aktivitas->fill($request->all());
        $aktivitas->user_id = Auth::id();

        // Handle file attachments if any
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('daily_aktivitas/attachments', $filename, 'public');
                $attachments[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }
            $aktivitas->attachments = $attachments;
        }

        $aktivitas->save();

        // Handle multi-user assignment
        $assignedUserIds = $request->get('assigned_users', []);

        // Add single assigned_to user if provided
        if ($request->filled('assigned_to') && !in_array($request->assigned_to, $assignedUserIds)) {
            $assignedUserIds[] = $request->assigned_to;
        }

        // Attach assigned users to pivot table
        if (!empty($assignedUserIds)) {
            $pivotData = [];
            foreach ($assignedUserIds as $userId) {
                $pivotData[$userId] = ['role' => 'assigned'];
            }
            $aktivitas->assignedUsers()->attach($pivotData);

            // Send notifications to assigned users
            app(\App\Services\NotificationService::class)->notifyDailyAktivitasCreated(
                $aktivitas,
                Auth::user(),
                $assignedUserIds
            );
        }

        return redirect()->route('daily-aktivitas.index')
            ->with('success', 'Aktivitas berhasil ditambahkan');
    }

    public function show(DailyAktivitas $dailyAktivitas)
    {
        $user = Auth::user();

        // Check if user can access this activity (creator, assigned_to, or assigned via pivot)
        $hasAccess = $dailyAktivitas->user_id === $user->id ||
            $dailyAktivitas->assigned_to === $user->id ||
            $dailyAktivitas->assignedUsers()->where('user_id', $user->id)->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
        }

        $dailyAktivitas->load(['user', 'assignedTo', 'assignedUsers']);

        return view('daily_aktivitas.show', compact('dailyAktivitas'));
    }

    public function edit(DailyAktivitas $dailyAktivitas)
    {
        $user = Auth::user();

        // Check if user can edit this activity (creator, assigned_to, or assigned via pivot)
        $hasAccess = $dailyAktivitas->user_id === $user->id ||
            $dailyAktivitas->assigned_to === $user->id ||
            $dailyAktivitas->assignedUsers()->where('user_id', $user->id)->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit aktivitas ini.');
        }

        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('daily_aktivitas.edit', compact('dailyAktivitas', 'users'));
    }

    public function update(Request $request, DailyAktivitas $dailyAktivitas)
    {
        $user = Auth::user();

        // Check if user can update this activity (allow assigned users to edit too)
        $isAssigned = $dailyAktivitas->assignedUsers()->where('user_id', $user->id)->exists();
        if ($dailyAktivitas->user_id !== $user->id && $dailyAktivitas->assigned_to !== $user->id && !$isAssigned) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate aktivitas ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_aktivitas' => 'required|in:' . implode(',', array_keys(DailyAktivitas::TIPE_AKTIVITAS)),
            'prioritas' => 'required|in:' . implode(',', array_keys(DailyAktivitas::PRIORITAS)),
            'status' => 'required|in:' . implode(',', array_keys(DailyAktivitas::STATUS)),
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deadline' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
            'hasil' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'peserta' => 'nullable|string',
            'estimasi_durasi' => 'nullable|numeric|min:0|max:999.99',
            'durasi_aktual' => 'nullable|numeric|min:0|max:999.99',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            'reminder_at' => 'nullable|date'
        ]);

        // Track changes for notifications
        $oldStatus = $dailyAktivitas->status;
        $changes = [];

        if ($request->deadline != $dailyAktivitas->deadline) {
            $changes['deadline'] = [
                'old' => $dailyAktivitas->deadline?->format('d/m/Y H:i'),
                'new' => $request->deadline ? \Carbon\Carbon::parse($request->deadline)->format('d/m/Y H:i') : null
            ];
        }

        if ($request->prioritas != $dailyAktivitas->prioritas) {
            $changes['prioritas'] = [
                'old' => $dailyAktivitas->prioritas_label,
                'new' => DailyAktivitas::PRIORITAS[$request->prioritas] ?? $request->prioritas
            ];
        }

        if ($request->judul != $dailyAktivitas->judul) {
            $changes['judul'] = ['old' => $dailyAktivitas->judul, 'new' => $request->judul];
        }

        if ($request->deskripsi != $dailyAktivitas->deskripsi) {
            $changes['deskripsi'] = ['old' => 'diubah', 'new' => 'diubah'];
        }

        // Get current assigned users before update
        $currentAssignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();

        $dailyAktivitas->fill($request->all());

        // Auto-set completion time if status changed to completed
        if ($request->status === 'done' && $dailyAktivitas->tanggal_selesai === null) {
            $dailyAktivitas->tanggal_selesai = \Carbon\Carbon::now();
        }

        // Handle new file attachments
        if ($request->hasFile('new_attachments')) {
            $currentAttachments = $dailyAktivitas->attachments ?? [];

            foreach ($request->file('new_attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('daily_aktivitas/attachments', $filename, 'public');
                $currentAttachments[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }

            $dailyAktivitas->attachments = $currentAttachments;
        }

        $dailyAktivitas->save();

        // Handle assignment changes
        $newAssignedUserIds = $request->get('assigned_users', []);

        // Add single assigned_to user if provided
        if ($request->filled('assigned_to') && !in_array($request->assigned_to, $newAssignedUserIds)) {
            $newAssignedUserIds[] = $request->assigned_to;
        }

        // Only owner can change assignments
        if ($dailyAktivitas->user_id === $user->id && $currentAssignedUserIds != $newAssignedUserIds) {
            $addedUsers = array_diff($newAssignedUserIds, $currentAssignedUserIds);
            $removedUsers = array_diff($currentAssignedUserIds, $newAssignedUserIds);

            // Update pivot table
            $pivotData = [];
            foreach ($newAssignedUserIds as $userId) {
                $pivotData[$userId] = ['role' => 'assigned'];
            }
            $dailyAktivitas->assignedUsers()->sync($pivotData);

            // Send assignment change notifications
            if (!empty($addedUsers) || !empty($removedUsers)) {
                app(\App\Services\NotificationService::class)->notifyDailyAktivitasAssignmentChanged(
                    $dailyAktivitas,
                    $addedUsers,
                    $removedUsers,
                    $user
                );
            }
        }

        // Send update notifications
        if (!empty($changes)) {
            app(\App\Services\NotificationService::class)->notifyDailyAktivitasUpdated(
                $dailyAktivitas,
                $user,
                $changes
            );
        }

        // Send status change notification
        if ($oldStatus !== $request->status) {
            app(\App\Services\NotificationService::class)->notifyDailyAktivitasStatusChanged(
                $dailyAktivitas,
                $oldStatus,
                $request->status,
                $user
            );
        }

        return redirect()->route('daily-aktivitas.show', $dailyAktivitas)
            ->with('success', 'Aktivitas berhasil diupdate');
    }

    public function destroy(DailyAktivitas $dailyAktivitas)
    {
        $user = Auth::user();

        // Check if user can delete this activity
        if ($dailyAktivitas->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus aktivitas ini.');
        }

        // Delete associated files if any
        if ($dailyAktivitas->attachments) {
            foreach ($dailyAktivitas->attachments as $attachment) {
                if (isset($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $dailyAktivitas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aktivitas berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, DailyAktivitas $dailyAktivitas)
    {
        $user = Auth::user();

        // Check if user can update this activity (allow assigned users to update too)
        $isAssigned = $dailyAktivitas->assignedUsers()->where('user_id', $user->id)->exists();
        if ($dailyAktivitas->user_id !== $user->id && $dailyAktivitas->assigned_to !== $user->id && !$isAssigned) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate aktivitas ini.');
        }

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(DailyAktivitas::STATUS))
        ]);

        $oldStatus = $dailyAktivitas->status;
        $dailyAktivitas->status = $request->status;

        // Auto-set completion time if status changed to done
        if ($request->status === 'done' && $dailyAktivitas->tanggal_selesai === null) {
            $dailyAktivitas->tanggal_selesai = \Carbon\Carbon::now();
        }

        $dailyAktivitas->save();

        // Send status change notification
        if ($oldStatus !== $request->status) {
            app(\App\Services\NotificationService::class)->notifyDailyAktivitasStatusChanged(
                $dailyAktivitas,
                $oldStatus,
                $request->status,
                $user
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Status aktivitas berhasil diupdate',
            'new_status' => $dailyAktivitas->status_label,
            'new_status_color' => $dailyAktivitas->status_color
        ]);
    }

    public function calendar(Request $request)
    {
        $user = Auth::user();

        $query = DailyAktivitas::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
        });

        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('tanggal_mulai', [
                Carbon::parse($request->start)->startOfDay(),
                Carbon::parse($request->end)->endOfDay()
            ]);
        }

        $aktivitas = $query->get();

        $events = $aktivitas->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->judul,
                'start' => Carbon::parse($item->tanggal_mulai)->toISOString(),
                'end' => $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->toISOString() : null,
                'color' => $this->getEventColor($item->prioritas, $item->status),
                'url' => route('daily-aktivitas.show', $item->id)
            ];
        });

        return response()->json($events);
    }

    private function getEventColor($prioritas, $status)
    {
        if ($status === 'selesai') return '#10b981'; // green
        if ($status === 'dibatalkan') return '#ef4444'; // red

        return match ($prioritas) {
            'urgent' => '#dc2626', // red
            'tinggi' => '#f59e0b', // orange
            'sedang' => '#3b82f6', // blue
            'rendah' => '#6b7280', // gray
            default => '#6b7280'
        };
    }
}
