<?php

namespace App\Services;

use App\Models\HealthRecord;

/**
 * HealthInsightsService - Provides personalized health recommendations
 * based on blood pressure and health data
 */
class HealthInsightsService
{
    /**
     * Generate recommendations based on health data
     */
    public function generateRecommendations(HealthRecord $record): array
    {
        $recommendations = [];

        // Analyze blood pressure readings
        $bpAnalysis = $this->analyzeBP($record->sistolik, $record->diastolik);
        $recommendations = array_merge($recommendations, $bpAnalysis);

        // Check heart rate
        $hrAnalysis = $this->analyzeHeartRate($record->denyut_nadi);
        $recommendations = array_merge($recommendations, $hrAnalysis);

        // Get history-based recommendations
        $patient = $record->patient;
        if ($patient) {
            $historyAnalysis = $this->analyzeHistory($patient);
            $recommendations = array_merge($recommendations, $historyAnalysis);
        }

        return [
            'recommendations' => $recommendations,
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Analyze blood pressure and provide diet/exercise recommendations
     */
    private function analyzeBP(int $sistolik, int $diastolik): array
    {
        $recommendations = [];

        if ($sistolik >= 160 || $diastolik >= 100) {
            // Stage 2 Hypertension
            $recommendations[] = [
                'type' => 'diet',
                'severity' => 'critical',
                'title' => 'Kurangi Asupan Garam',
                'description' => 'Konsumsi garam kurang dari 2.3g per hari. Hindari makanan olahan dan makanan cepat saji.',
                'priority' => 1,
            ];
            $recommendations[] = [
                'type' => 'exercise',
                'severity' => 'critical',
                'title' => 'Konsultasi Dokter Segera',
                'description' => 'Tekanan darah Anda sangat tinggi. Segera hubungi dokter untuk pemeriksaan lebih lanjut.',
                'priority' => 1,
            ];
        } elseif ($sistolik >= 140 || $diastolik >= 90) {
            // Stage 1 Hypertension
            $recommendations[] = [
                'type' => 'diet',
                'severity' => 'high',
                'title' => 'Perbanyak Konsumsi Kalium',
                'description' => 'Makan pisang, jeruk, dan sayuran berdaun hijau untuk membantu menurunkan tekanan darah.',
                'priority' => 2,
            ];
            $recommendations[] = [
                'type' => 'exercise',
                'severity' => 'high',
                'title' => 'Tingkatkan Aktivitas Fisik',
                'description' => 'Olahraga minimal 30 menit per hari, 5 hari seminggu. Berjalan kaki, berenang, atau yoga sangat baik.',
                'priority' => 2,
            ];
            $recommendations[] = [
                'type' => 'lifestyle',
                'severity' => 'high',
                'title' => 'Kurangi Stres',
                'description' => 'Coba meditasi, teknik relaksasi, atau konseling untuk mengelola stres.',
                'priority' => 3,
            ];
        } elseif ($sistolik >= 120 || $diastolik >= 80) {
            // Prehypertension
            $recommendations[] = [
                'type' => 'diet',
                'severity' => 'medium',
                'title' => 'Perbaiki Pola Makan',
                'description' => 'Ikuti diet DASH: banyak buah, sayur, biji-bijian, dan kurangi lemak jenuh.',
                'priority' => 2,
            ];
            $recommendations[] = [
                'type' => 'exercise',
                'severity' => 'medium',
                'title' => 'Mulai Rutin Berolahraga',
                'description' => 'Mulai dengan aktivitas ringan seperti jalan kaki 20-30 menit setiap hari.',
                'priority' => 3,
            ];
        } else {
            // Normal
            $recommendations[] = [
                'type' => 'lifestyle',
                'severity' => 'low',
                'title' => 'Pertahankan Gaya Hidup Sehat',
                'description' => 'Terus jaga pola makan sehat, olahraga teratur, dan istirahat yang cukup.',
                'priority' => 3,
            ];
        }

        return $recommendations;
    }

    /**
     * Analyze heart rate
     */
    private function analyzeHeartRate(int $denyutNadi): array
    {
        $recommendations = [];

        if ($denyutNadi > 100) {
            $recommendations[] = [
                'type' => 'health',
                'severity' => 'medium',
                'title' => 'Detak Jantung Tinggi',
                'description' => 'Istirahat yang cukup dan hindari kafein. Jika berlanjut, konsultasi dengan dokter.',
                'priority' => 2,
            ];
        } elseif ($denyutNadi < 60 && $denyutNadi > 0) {
            $recommendations[] = [
                'type' => 'health',
                'severity' => 'low',
                'title' => 'Detak Jantung Rendah',
                'description' => 'Ini bisa normal jika Anda aktif secara fisik. Pantau terus kondisi Anda.',
                'priority' => 3,
            ];
        }

        return $recommendations;
    }

    /**
     * Analyze patient's health history and provide trend-based recommendations
     */
    private function analyzeHistory($patient): array
    {
        $recommendations = [];

        // Get last 7 days average
        $avgStats = \App\Models\HealthRecord::getStatsForPatient($patient->id, 7);

        if ($avgStats && $avgStats->avg_sistolik >= 140) {
            $recommendations[] = [
                'type' => 'trend',
                'severity' => 'high',
                'title' => 'Tekanan Darah Meningkat',
                'description' => 'Rata-rata tekanan darah Anda meningkat dalam 7 hari terakhir. Monitor lebih sering.',
                'priority' => 2,
            ];
        }

        // Check streak
        if ($patient->streak_days >= 30) {
            $recommendations[] = [
                'type' => 'motivation',
                'severity' => 'low',
                'title' => '30 Hari Konsisten!',
                'description' => 'Luar biasa! Anda telah mencatat data selama 30 hari berturut-turut. Teruskan!',
                'priority' => 3,
            ];
        }

        return $recommendations;
    }

    /**
     * Get simple risk assessment
     */
    public function getRiskLevel(int $sistolik, int $diastolik): string
    {
        if ($sistolik >= 160 || $diastolik >= 100) {
            return 'critical';
        } elseif ($sistolik >= 140 || $diastolik >= 90) {
            return 'high';
        } elseif ($sistolik >= 120 || $diastolik >= 80) {
            return 'medium';
        } else {
            return 'low';
        }
    }
}
