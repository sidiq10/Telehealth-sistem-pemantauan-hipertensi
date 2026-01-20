<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\HealthInsightsService;

class HealthInsightsServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HealthInsightsService();
    }

    public function test_normal_bp_generates_maintenance_recommendation()
    {
        $recommendations = $this->service->generateRecommendations(
            $this->createMockHealthRecord(115, 75)
        );

        $this->assertNotEmpty($recommendations['recommendations']);
        $this->assertTrue(
            collect($recommendations['recommendations'])->contains('severity', 'low')
        );
    }

    public function test_prehypertension_generates_diet_and_exercise_recommendations()
    {
        $recommendations = $this->service->generateRecommendations(
            $this->createMockHealthRecord(125, 82)
        );

        $this->assertNotEmpty($recommendations['recommendations']);
        $this->assertTrue(
            collect($recommendations['recommendations'])->contains('type', 'diet')
        );
        $this->assertTrue(
            collect($recommendations['recommendations'])->contains('type', 'exercise')
        );
    }

    public function test_stage_2_hypertension_triggers_critical_alert()
    {
        $recommendations = $this->service->generateRecommendations(
            $this->createMockHealthRecord(160, 100)
        );

        $this->assertTrue(
            collect($recommendations['recommendations'])->contains('severity', 'critical')
        );
    }

    public function test_risk_level_classification()
    {
        $this->assertEquals('low', $this->service->getRiskLevel(115, 75));
        $this->assertEquals('medium', $this->service->getRiskLevel(125, 82));
        $this->assertEquals('high', $this->service->getRiskLevel(145, 90));
        $this->assertEquals('critical', $this->service->getRiskLevel(160, 100));
    }

    private function createMockHealthRecord($sistolik, $diastolik)
    {
        $mock = \Mockery::mock('App\Models\HealthRecord');
        $mock->sistolik = $sistolik;
        $mock->diastolik = $diastolik;
        $mock->denyut_nadi = 72;
        $mock->patient = null;

        return $mock;
    }
}
