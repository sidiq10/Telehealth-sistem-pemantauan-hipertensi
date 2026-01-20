<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class DataEntryReminder extends Notification
{
    use Queueable;

    public function __construct(public User $patient)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Reminder: Masukkan Data Kesehatan',
            'message' => 'Jangan lupa untuk mencatat data tekanan darah Anda hari ini!',
            'type' => 'reminder',
            'action_url' => '/dashboard/health-records/create',
        ];
    }

    public function toMail(object $notifiable)
    {
        return null; // Use view-based mail instead
    }
}
