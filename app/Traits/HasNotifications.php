<?php

namespace App\Traits;

trait HasNotifications
{
    public function sendNotification($users, $title, $message, $type = 'info', $link = null)
    {
        if (!is_array($users)) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $user->notifications()->create([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'link' => $link
            ]);
        }
    }
}
