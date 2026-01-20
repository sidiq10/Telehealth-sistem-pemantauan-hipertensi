<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GamificationService;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GamificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private $gamificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gamificationService = new GamificationService();
    }

    public function test_awards_points_for_entry()
    {
        $user = User::factory()->create(['points' => 0]);

        $this->gamificationService->awardPointsForEntry($user);

        $user->refresh();
        $this->assertEquals(10, $user->points);
    }

    public function test_updates_streak_on_consecutive_days()
    {
        $user = User::factory()->create([
            'last_data_entry_date' => today()->subDay(),
            'streak_days' => 1,
        ]);

        $this->gamificationService->updateStreak($user);

        $user->refresh();
        $this->assertEquals(2, $user->streak_days);
        $this->assertEquals(today(), $user->last_data_entry_date);
    }

    public function test_resets_streak_on_missed_day()
    {
        $user = User::factory()->create([
            'last_data_entry_date' => today()->subDays(2),
            'streak_days' => 5,
        ]);

        $this->gamificationService->updateStreak($user);

        $user->refresh();
        $this->assertEquals(1, $user->streak_days);
    }

    public function test_get_progress_returns_correct_data()
    {
        $user = User::factory()->create([
            'points' => 150,
            'streak_days' => 7,
        ]);

        $progress = $this->gamificationService->getProgress($user);

        $this->assertEquals(150, $progress['points']);
        $this->assertEquals(7, $progress['streak_days']);
    }
}
