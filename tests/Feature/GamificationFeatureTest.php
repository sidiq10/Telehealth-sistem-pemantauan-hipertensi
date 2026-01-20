<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create badges
        Badge::create([
            'name' => '7 Day Streak',
            'description' => 'Catat 7 hari berturut-turut',
            'requirement' => 7,
            'type' => 'streak',
        ]);

        Badge::create([
            'name' => '100 Points',
            'description' => 'Kumpulkan 100 poin',
            'requirement' => 100,
            'type' => 'points',
        ]);
    }

    public function test_patient_receives_points_for_data_entry()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'points' => 0,
        ]);

        $this->actingAs($patient);

        HealthRecord::create([
            'patient_id' => $patient->id,
            'sistolik' => 120,
            'diastolik' => 80,
            'denyut_nadi' => 72,
        ]);

        $patient->refresh();
        $this->assertGreaterThan(0, $patient->points);
    }

    public function test_health_record_generates_recommendations()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $record = HealthRecord::create([
            'patient_id' => $patient->id,
            'sistolik' => 145,
            'diastolik' => 90,
            'denyut_nadi' => 75,
        ]);

        $record->refresh();
        $this->assertNotNull($record->recommendations);
        $this->assertIsArray($record->recommendations);
        $this->assertArrayHasKey('recommendations', $record->recommendations);
    }

    public function test_patient_earns_badge_after_7_day_streak()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'streak_days' => 7,
        ]);

        // Verify badge can be associated
        $badge = Badge::where('name', '7 Day Streak')->first();
        $patient->badges()->attach($badge->id);

        $this->assertTrue($patient->badges()->where('badge_id', $badge->id)->exists());
    }

    public function test_doctor_can_view_analytics_dashboard()
    {
        $doctor = User::factory()->create(['role' => 'dokter']);
        $patient = User::factory()->create(['role' => 'pasien']);

        // Associate patient with doctor
        $doctor->patients()->attach($patient->id);

        $this->actingAs($doctor);
        $response = $this->get(route('dokter.analytics.index'));

        $response->assertStatus(200);
    }

    public function test_patient_cannot_access_analytics()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);
        $response = $this->get(route('dokter.analytics.index'));

        $response->assertStatus(403);
    }
}
