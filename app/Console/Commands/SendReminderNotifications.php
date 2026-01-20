<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Services\GamificationService;

class SendReminderNotifications extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send reminder notifications to patients who haven\'t entered health data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $notificationService = new NotificationService();
        $gamificationService = new GamificationService();

        // Get patients who need reminders
        $patients = $notificationService->getPatientsDueForReminder();

        if ($patients->isEmpty()) {
            $this->info('No patients need reminders at this time.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($patients as $patient) {
            try {
                $notificationService->sendDataEntryReminder($patient);
                $count++;
                $this->line("✓ Reminder sent to {$patient->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to {$patient->email}: " . $e->getMessage());
            }
        }

        // Check and reset streaks
        $gamificationService->checkAndResetStreaks();

        $this->info("\nReminders sent to {$count} patient(s).");
        return Command::SUCCESS;
    }
}
