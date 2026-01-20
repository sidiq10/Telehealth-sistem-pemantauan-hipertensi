<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class FeedbackRequest extends Notification
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
            'title' => 'Feedback Request',
            'message' => 'Bagikan pengalaman Anda menggunakan layanan telehealth kami.',
            'type' => 'feedback',
            'action_url' => '/dashboard/feedbacks/create',
        ];
    }

    public function toMail(object $notifiable)
    {
        return null;
    }
}
