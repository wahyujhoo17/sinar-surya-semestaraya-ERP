<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to users based on their roles
     *
     * @param array $roleIds Array of role IDs or role codes
     * @param string $type Notification type (order, payment, approval, etc.)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data (URLs, IDs, etc.)
     * @return void
     */
    public function sendToRoles(array $roles, string $type, string $title, string $message, array $data = [])
    {
        try {
            // Get users with the specified roles
            $users = User::whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('kode', $roles);
            })->where('is_active', true)->get();

            foreach ($users as $user) {
                $this->createNotification($user->id, $type, $title, $message, $data);
            }

            Log::info('Notifications sent to roles', [
                'roles' => $roles,
                'type' => $type,
                'user_count' => $users->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notifications to roles', [
                'roles' => $roles,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification to specific users
     *
     * @param array $userIds Array of user IDs
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data
     * @return void
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $data = [])
    {
        try {
            foreach ($userIds as $userId) {
                $this->createNotification($userId, $type, $title, $message, $data);
            }

            Log::info('Notifications sent to users', [
                'user_ids' => $userIds,
                'type' => $type,
                'count' => count($userIds)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notifications to users', [
                'user_ids' => $userIds,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send Daily Aktivitas notifications
     */
    public function notifyDailyAktivitasCreated($dailyAktivitas, $createdBy, $assignedUserIds = [])
    {
        if (empty($assignedUserIds)) {
            return;
        }

        $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
        $assignedNames = implode(', ', $assignedUsers);

        // Notify assigned users
        $this->sendToUsers(
            $assignedUserIds,
            'order',
            'Aktivitas Baru Ditugaskan',
            "Anda ditugaskan pada aktivitas '{$dailyAktivitas->judul}' oleh {$createdBy->name}. " .
                "Status: {$dailyAktivitas->status}" .
                ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'assigned_by' => $createdBy->id,
                'deadline' => $dailyAktivitas->deadline?->toISOString()
            ]
        );

        // Notify managers about new task assignment
        $this->sendToRoles(
            ['manager', 'admin'],
            'order',
            'Aktivitas Baru Dibuat',
            "{$createdBy->name} membuat aktivitas '{$dailyAktivitas->judul}' dan menugaskan ke: {$assignedNames}",
            [
                'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                'daily_aktivitas_id' => $dailyAktivitas->id,
                'created_by' => $createdBy->id,
                'assigned_users_count' => count($assignedUserIds)
            ]
        );
    }

    public function notifyDailyAktivitasUpdated($dailyAktivitas, $updatedBy, $changes = [])
    {
        // Get all assigned users
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();

        if (empty($assignedUserIds)) {
            return;
        }

        $changeText = '';
        if (!empty($changes)) {
            $changeMessages = [];
            foreach ($changes as $field => $change) {
                if ($field === 'deadline') {
                    $changeMessages[] = "Deadline: {$change['old']} → {$change['new']}";
                } elseif ($field === 'prioritas') {
                    $changeMessages[] = "Prioritas: {$change['old']} → {$change['new']}";
                } elseif ($field === 'judul') {
                    $changeMessages[] = "Judul diubah";
                } elseif ($field === 'deskripsi') {
                    $changeMessages[] = "Deskripsi diubah";
                }
            }
            $changeText = ' Perubahan: ' . implode(', ', $changeMessages);
        }

        // Notify assigned users (except the one who updated)
        $notifyUserIds = array_filter($assignedUserIds, function ($id) use ($updatedBy) {
            return $id !== $updatedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Aktivitas Diperbarui',
                "Aktivitas '{$dailyAktivitas->judul}' telah diperbarui oleh {$updatedBy->name}.{$changeText}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'updated_by' => $updatedBy->id,
                    'changes' => $changes
                ]
            );
        }
    }

    public function notifyDailyAktivitasStatusChanged($dailyAktivitas, $oldStatus, $newStatus, $changedBy)
    {
        $assignedUserIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();

        if (empty($assignedUserIds)) {
            return;
        }

        $statusMessages = [
            'todo' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'review' => 'Review',
            'done' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        $oldStatusText = $statusMessages[$oldStatus] ?? $oldStatus;
        $newStatusText = $statusMessages[$newStatus] ?? $newStatus;

        $notificationType = 'order';
        if ($newStatus === 'done') {
            $notificationType = 'success';
        } elseif ($newStatus === 'cancelled') {
            $notificationType = 'warning';
        }

        // Notify assigned users (except the one who changed)
        $notifyUserIds = array_filter($assignedUserIds, function ($id) use ($changedBy) {
            return $id !== $changedBy->id;
        });

        if (!empty($notifyUserIds)) {
            $this->sendToUsers(
                $notifyUserIds,
                $notificationType,
                'Status Aktivitas Berubah',
                "Status aktivitas '{$dailyAktivitas->judul}' berubah dari {$oldStatusText} menjadi {$newStatusText} oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => $changedBy->id
                ]
            );
        }

        // Notify managers when task is completed
        if ($newStatus === 'done') {
            $assignedUsers = User::whereIn('id', $assignedUserIds)->pluck('name')->toArray();
            $assignedNames = implode(', ', $assignedUsers);

            $this->sendToRoles(
                ['manager', 'admin'],
                'success',
                'Aktivitas Selesai',
                "Aktivitas '{$dailyAktivitas->judul}' telah diselesaikan oleh {$changedBy->name}. Tim: {$assignedNames}",
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'completed_by' => $changedBy->id
                ]
            );
        }
    }

    public function notifyDailyAktivitasAssignmentChanged($dailyAktivitas, $addedUsers, $removedUsers, $changedBy)
    {
        // Notify newly added users
        if (!empty($addedUsers)) {
            $this->sendToUsers(
                $addedUsers,
                'order',
                'Ditugaskan ke Aktivitas',
                "Anda ditugaskan ke aktivitas '{$dailyAktivitas->judul}' oleh {$changedBy->name}. " .
                    "Status: {$dailyAktivitas->status}" .
                    ($dailyAktivitas->deadline ? " | Deadline: " . $dailyAktivitas->deadline->format('d/m/Y H:i') : ''),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'assigned_by' => $changedBy->id
                ]
            );
        }

        // Notify removed users
        if (!empty($removedUsers)) {
            $this->sendToUsers(
                $removedUsers,
                'order',
                'Penugasan Dihapus',
                "Penugasan Anda pada aktivitas '{$dailyAktivitas->judul}' telah dihapus oleh {$changedBy->name}",
                [
                    'url' => route('daily-aktivitas.index'),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'removed_by' => $changedBy->id
                ]
            );
        }

        // Notify remaining assigned users about team changes
        $currentAssignedIds = $dailyAktivitas->assignedUsers()->pluck('user_id')->toArray();
        $notifyUserIds = array_filter($currentAssignedIds, function ($id) use ($changedBy, $addedUsers) {
            return $id !== $changedBy->id && !in_array($id, $addedUsers);
        });

        if (!empty($notifyUserIds) && (!empty($addedUsers) || !empty($removedUsers))) {
            $changeText = [];
            if (!empty($addedUsers)) {
                $addedNames = User::whereIn('id', $addedUsers)->pluck('name')->toArray();
                $changeText[] = 'Ditambahkan: ' . implode(', ', $addedNames);
            }
            if (!empty($removedUsers)) {
                $removedNames = User::whereIn('id', $removedUsers)->pluck('name')->toArray();
                $changeText[] = 'Dihapus: ' . implode(', ', $removedNames);
            }

            $this->sendToUsers(
                $notifyUserIds,
                'order',
                'Tim Aktivitas Berubah',
                "Tim aktivitas '{$dailyAktivitas->judul}' diubah oleh {$changedBy->name}. " . implode('. ', $changeText),
                [
                    'url' => route('daily-aktivitas.show', $dailyAktivitas->id),
                    'daily_aktivitas_id' => $dailyAktivitas->id,
                    'changed_by' => $changedBy->id,
                    'added_users' => $addedUsers,
                    'removed_users' => $removedUsers
                ]
            );
        }
    }

    /**
     * Create notification record
     *
     * @param int $userId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @return void
     */
    private function createNotification(int $userId, string $type, string $title, string $message, array $data = [])
    {
        try {
            // Fix URL to use current domain instead of localhost
            $url = $data['url'] ?? null;
            if ($url) {
                $url = $this->fixNotificationUrl($url);
            }

            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $url,
                'data' => json_encode($data),
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fix notification URL to use current domain instead of localhost
     */
    private function fixNotificationUrl($url)
    {
        if (!$url) {
            return $url;
        }

        $originalUrl = $url;

        // Normalize multiple :8000 in the URL (fixes http://localhost:8000:8000:8000/...)
        $url = preg_replace('/(:\d+)(:\d+)+/', '$1', $url);

        // If URL is absolute and contains localhost, replace with current domain
        // Remove duplicate ports in localhost URLs (e.g., :8000:8000:8000)
        if (is_string($url)) {
            // Replace any sequence of :port repeated with just one
            $url = preg_replace('/(:\d+)(:\d+)+/', '$1', $url);

            // If still starts with localhost, replace with current domain
            if (preg_match('#^https?://localhost(:\d+)?#', $url)) {
                $currentUrl = request()->getSchemeAndHttpHost();
                // Remove everything up to and including the last port in localhost
                $url = preg_replace('#^https?://localhost(:\d+)?#', $currentUrl, $url);

                Log::info('Fixed notification URL from localhost', [
                    'original' => $originalUrl,
                    'fixed' => $url,
                    'current_domain' => $currentUrl
                ]);
            }
        }

        // Also handle cases where APP_URL is localhost but request is from different domain
        $appUrl = config('app.url');
        if ($appUrl && strpos($appUrl, 'localhost') !== false && is_string($url) && strpos($url, $appUrl) === 0) {
            $currentUrl = request()->getSchemeAndHttpHost();
            $url = str_replace($appUrl, $currentUrl, $url);

            Log::info('Fixed notification URL from APP_URL localhost', [
                'original' => $originalUrl,
                'fixed' => $url,
                'app_url' => $appUrl,
                'current_domain' => $currentUrl
            ]);
        }

        return $url;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId = null)
    {
        $query = Notification::where('id', $notificationId);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $query->update(['read_at' => now()]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
