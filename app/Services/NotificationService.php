<?php

namespace App\Services;

use App\Models\User;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * NotificationService - Handles reminder notifications
 */
class NotificationService
{
    const DAYS_WITHOUT_ENTRY = 3;
    const MAX_REMINDERS_PER_WEEK = 2;

    /**
     * Check patients who need reminders
     */
    public function getPatientsDueForReminder(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', 'pasien')
                   ->where(function ($query) {
                       // No entry in last 3 days
                       $query->whereDoesntHave('healthRecords', function ($q) {
                           $q->where('created_at', '>=', Carbon::now()->subDays(self::DAYS_WITHOUT_ENTRY));
                       })->orWhereHas('healthRecords', function ($q) {
                           $q->where('created_at', '<', Carbon::now()->subDays(self::DAYS_WITHOUT_ENTRY));
                       }, '=', 0);
                   })
                   ->get();
    }

    /**
     * Send reminder notification to patient
     */
    public function sendDataEntryReminder(User $patient): void
    {
        if (!$patient->email) {
            return;
        }

        // Store in database notification
        $patient->notify(new \App\Notifications\DataEntryReminder($patient));

        // Also send email
        try {
            Mail::send('emails.reminder-data-entry', ['patient' => $patient], function ($message) use ($patient) {
                $message->to($patient->email)
                        ->subject('Reminder: Masukkan Data Kesehatan Anda');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send data entry reminder email: ' . $e->getMessage());
        }
    }

    /**
     * Send consultation reminder
     */
    public function sendConsultationReminder(User $patient): void
    {
        if (!$patient->email) {
            return;
        }

        // Check if patient has pending consultation requests
        // This is a placeholder - adjust based on your consultation model
        $patient->notify(new \App\Notifications\ConsultationReminder($patient));

        try {
            Mail::send('emails.reminder-consultation', ['patient' => $patient], function ($message) use ($patient) {
                $message->to($patient->email)
                        ->subject('Reminder: Jadwalkan Konsultasi dengan Dokter');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send consultation reminder email: ' . $e->getMessage());
        }
    }

    /**
     * Send feedback request
     */
    public function sendFeedbackRequest(User $patient): void
    {
        if (!$patient->email) {
            return;
        }

        $patient->notify(new \App\Notifications\FeedbackRequest($patient));

        try {
            Mail::send('emails.feedback-request', ['patient' => $patient], function ($message) use ($patient) {
                $message->to($patient->email)
                        ->subject('Kami ingin mendengar pendapat Anda tentang layanan kami');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send feedback request email: ' . $e->getMessage());
        }
    }
}
