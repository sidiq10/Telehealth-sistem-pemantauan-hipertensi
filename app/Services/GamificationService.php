<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use Carbon\Carbon;

/**
 * GamificationService - Handles points, badges, and streaks
 */
class GamificationService
{
    const POINTS_FOR_DATA_ENTRY = 10;
    const POINTS_FOR_7_DAY_STREAK = 50;
    const POINTS_FOR_30_DAY_STREAK = 200;

    /**
     * Award points for data entry
     */
    public function awardPointsForEntry(User $user): int
    {
        $points = self::POINTS_FOR_DATA_ENTRY;
        $user->increment('points', $points);

        $this->updateStreak($user);
        $this->checkAndAwardBadges($user);

        return $points;
    }

    /**
     * Update user's streak based on entry history
     */
    public function updateStreak(User $user): void
    {
        $today = today();
        $lastEntryDate = $user->last_data_entry_date;

        if ($lastEntryDate === null) {
            // First entry
            $user->update([
                'last_data_entry_date' => $today,
                'streak_days' => 1,
            ]);
        } elseif ($lastEntryDate->diffInDays($today) === 1) {
            // Consecutive day
            $user->increment('streak_days');
            $user->update(['last_data_entry_date' => $today]);
        } elseif ($lastEntryDate->diffInDays($today) > 1) {
            // Streak broken
            $user->update([
                'last_data_entry_date' => $today,
                'streak_days' => 1,
            ]);
        }
        // If same day, do nothing
    }

    /**
     * Check and award badges
     */
    public function checkAndAwardBadges(User $user): void
    {
        $existingBadges = $user->badges()->pluck('badge_id')->toArray();

        // Check streak badges
        if ($user->streak_days === 7 && !in_array($this->getBadgeIdByKey('7_day_streak'), $existingBadges)) {
            $this->awardBadge($user, '7_day_streak');
            $user->increment('points', 50);
        }

        if ($user->streak_days === 30 && !in_array($this->getBadgeIdByKey('30_day_streak'), $existingBadges)) {
            $this->awardBadge($user, '30_day_streak');
            $user->increment('points', 200);
        }

        // Check points badges
        if ($user->points >= 100 && !in_array($this->getBadgeIdByKey('100_points'), $existingBadges)) {
            $this->awardBadge($user, '100_points');
        }

        if ($user->points >= 500 && !in_array($this->getBadgeIdByKey('500_points'), $existingBadges)) {
            $this->awardBadge($user, '500_points');
        }
    }

    /**
     * Award a badge to user
     */
    public function awardBadge(User $user, string $badgeKey): void
    {
        $badge = Badge::where('name', ucfirst(str_replace('_', ' ', $badgeKey)))->first();

        if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['earned_at' => now()]);
        }
    }

    /**
     * Get badge ID by key
     */
    private function getBadgeIdByKey(string $key): ?int
    {
        $badge = Badge::where('name', ucfirst(str_replace('_', ' ', $key)))->first();
        return $badge?->id;
    }

    /**
     * Reset streak if no entry for more than 1 day
     */
    public function checkAndResetStreaks(): void
    {
        $yesterday = today()->subDay();

        User::where('streak_days', '>', 0)
            ->where('last_data_entry_date', '<', $yesterday)
            ->update(['streak_days' => 0]);
    }

    /**
     * Get user's gamification progress
     */
    public function getProgress(User $user): array
    {
        return [
            'points' => $user->points,
            'streak_days' => $user->streak_days,
            'badges_count' => $user->badges()->count(),
            'badges' => $user->badges()->get(['name', 'description', 'icon_url']),
            'next_badge' => $this->getNextBadge($user),
            'points_to_next_milestone' => $this->getPointsToNextMilestone($user),
        ];
    }

    /**
     * Get next badge the user can earn
     */
    private function getNextBadge(User $user): ?array
    {
        $existingBadges = $user->badges()->pluck('badge_id')->toArray();

        $nextBadge = Badge::whereNotIn('id', $existingBadges)
                          ->orderBy('requirement')
                          ->first();

        if ($nextBadge) {
            $progress = 0;
            if ($nextBadge->type === 'streak') {
                $progress = min($user->streak_days, $nextBadge->requirement);
            } elseif ($nextBadge->type === 'points') {
                $progress = min($user->points, $nextBadge->requirement);
            }

            return [
                'name' => $nextBadge->name,
                'description' => $nextBadge->description,
                'requirement' => $nextBadge->requirement,
                'progress' => $progress,
                'percentage' => ($progress / $nextBadge->requirement) * 100,
            ];
        }

        return null;
    }

    /**
     * Get points until next milestone
     */
    private function getPointsToNextMilestone(User $user): int
    {
        $milestones = [100, 500, 1000, 2500, 5000];
        $currentPoints = $user->points;

        foreach ($milestones as $milestone) {
            if ($currentPoints < $milestone) {
                return $milestone - $currentPoints;
            }
        }

        return 0;
    }
}
