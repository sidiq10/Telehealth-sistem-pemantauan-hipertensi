<?php

namespace App\Observers;

use App\Models\HealthRecord;
use App\Services\HealthInsightsService;
use App\Services\GamificationService;

class HealthRecordObserver
{
    protected $healthInsights;
    protected $gamification;

    public function __construct(HealthInsightsService $healthInsights, GamificationService $gamification)
    {
        $this->healthInsights = $healthInsights;
        $this->gamification = $gamification;
    }

    /**
     * Handle the HealthRecord "created" event.
     */
    public function created(HealthRecord $healthRecord): void
    {
        // Generate recommendations
        $recommendations = $this->healthInsights->generateRecommendations($healthRecord);
        $healthRecord->update(['recommendations' => $recommendations]);

        // Award points and check badges
        $this->gamification->awardPointsForEntry($healthRecord->patient);
    }

    /**
     * Handle the HealthRecord "updated" event.
     */
    public function updated(HealthRecord $healthRecord): void
    {
        //
    }

    /**
     * Handle the HealthRecord "deleted" event.
     */
    public function deleted(HealthRecord $healthRecord): void
    {
        //
    }
}
