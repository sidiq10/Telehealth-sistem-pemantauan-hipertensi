<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ConsultationReminder extends Notification
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
            'title' => 'Reminder: Jadwalkan Konsultasi',
            'message' => 'Hubungi dokter Anda untuk menjadwalkan konsultasi kesehatan.',
            'type' => 'consultation',
            'action_url' => '/dashboard/consultations',
        ];
    }

    public function toMail(object $notifiable)
    {
        return null;
    }
}
