<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\HealthRecord;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display doctor's analytics dashboard
     */
    public function index(): View
    {
        $doctor = Auth::user();

        // Verify user is a doctor
        if ($doctor->role !== 'dokter') {
            abort(403, 'Hanya dokter yang dapat mengakses analytics.');
        }

        $patients = $doctor->patients()->get();
        $patientIds = $patients->pluck('id')->toArray();

        // Feedback analytics
        $feedbackStats = $this->getFeedbackStats($patientIds);

        // Patient engagement stats
        $engagementStats = collect($this->getEngagementStats($patients));

        // Health trend analytics
        $healthTrends = $this->getHealthTrends($patientIds);
        
        // Aggregate health trends for dashboard display
        $aggregatedTrends = [
            'Normal' => 0,
            'Prehipertensi' => 0,
            'Hipertensi Stage 1' => 0,
            'Hipertensi Stage 2' => 0,
        ];
        
        foreach ($healthTrends as $trend) {
            $aggregatedTrends['Normal'] += $trend['status_distribution']['normal'] ?? 0;
            $aggregatedTrends['Prehipertensi'] += $trend['status_distribution']['prehypertension'] ?? 0;
            $aggregatedTrends['Hipertensi Stage 1'] += $trend['status_distribution']['stage1'] ?? 0;
            $aggregatedTrends['Hipertensi Stage 2'] += $trend['status_distribution']['stage2'] ?? 0;
        }

        // Get warning patients (Stage 2 Hypertension or Critical readings)
        $warningPatients = collect([]);
        foreach ($healthTrends as $patientId => $trend) {
            if ($trend['current_status'] === 'Hipertensi Stage 2') {
                $patient = User::find($patientId);
                if ($patient) {
                    $warningPatients->push($patient);
                }
            }
        }

        // Calculate key metrics
        $totalPatients = $patients->count();
        $totalFeedbacks = Feedback::whereIn('receiver_id', $patientIds)->count();
        $avgRating = Feedback::whereIn('receiver_id', $patientIds)->avg('rating') ?? 0;
        
        // Calculate engagement score (0-100)
        $totalEntries = HealthRecord::whereIn('patient_id', $patientIds)->count();
        $engagementScore = $totalPatients > 0 ? min(100, ($totalEntries / ($totalPatients * 4)) * 100) : 0;

        // Feedback distribution
        $feedbackDistribution = [
            'excellent' => Feedback::whereIn('receiver_id', $patientIds)->where('rating', 5)->count(),
            'good' => Feedback::whereIn('receiver_id', $patientIds)->where('rating', 4)->count(),
            'neutral' => Feedback::whereIn('receiver_id', $patientIds)->where('rating', 3)->count(),
            'poor' => Feedback::whereIn('receiver_id', $patientIds)->where('rating', '<=', 2)->count(),
        ];

        return view('dokter.analytics.dashboard', [
            'totalPatients' => $totalPatients,
            'totalFeedbacks' => $totalFeedbacks,
            'avgRating' => round($avgRating, 1),
            'engagementScore' => round($engagementScore, 1),
            'feedbackDistribution' => $feedbackDistribution,
            'healthTrends' => $aggregatedTrends,
            'warningPatients' => $warningPatients,
            'feedbackStats' => $feedbackStats,
            'patientEngagement' => $engagementStats,
            'patients' => $patients,
        ]);
    }

    /**
     * Get feedback analytics
     */
    private function getFeedbackStats(array $patientIds): array
    {
        $feedbacks = Feedback::whereIn('receiver_id', $patientIds)
                             ->where('sender_id', '!=', Auth::id())
                             ->get();

        $totalFeedback = $feedbacks->count();
        $avgRating = $feedbacks->whereNotNull('rating')->avg('rating') ?? 0;
        $ratedFeedbacks = $feedbacks->whereNotNull('rating')->count();
        $anonymousFeedbacks = $feedbacks->where('anonymous', true)->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $feedbacks->where('rating', $i)->count();
        }

        // Feedback by patient
        $feedbackByPatient = [];
        foreach ($patientIds as $patientId) {
            $patientFeedbacks = $feedbacks->where('receiver_id', $patientId);
            $feedbackByPatient[$patientId] = [
                'count' => $patientFeedbacks->count(),
                'avg_rating' => $patientFeedbacks->whereNotNull('rating')->avg('rating') ?? 0,
                'unread' => $patientFeedbacks->where('is_read', false)->count(),
            ];
        }

        return [
            'total' => $totalFeedback,
            'average_rating' => round($avgRating, 2),
            'rated_feedbacks' => $ratedFeedbacks,
            'anonymous_feedbacks' => $anonymousFeedbacks,
            'rating_distribution' => $ratingDistribution,
            'by_patient' => $feedbackByPatient,
        ];
    }

    /**
     * Get patient engagement statistics
     */
    private function getEngagementStats($patients): array
    {
        $stats = [];

        foreach ($patients as $patient) {
            $totalRecords = HealthRecord::where('patient_id', $patient->id)->count();
            $recentRecords = HealthRecord::where('patient_id', $patient->id)
                                        ->where('created_at', '>=', now()->subDays(30))
                                        ->count();
            $lastEntry = HealthRecord::where('patient_id', $patient->id)
                                     ->latest('created_at')
                                     ->first()?->created_at;

            $stats[] = [
                'name' => $patient->name,
                'points' => $patient->points,
                'entries' => $totalRecords,
                'feedbacks' => Feedback::where('receiver_id', $patient->id)->where('sender_id', '!=', Auth::id())->count(),
                'lastEntry' => $lastEntry,
            ];
        }

        // Sort by points descending
        usort($stats, fn($a, $b) => $b['points'] <=> $a['points']);

        return $stats;
    }

    /**
     * Get health trend analytics
     */
    private function getHealthTrends(array $patientIds): array
    {
        $trends = [];

        foreach ($patientIds as $patientId) {
            $records = HealthRecord::where('patient_id', $patientId)
                                   ->where('created_at', '>=', now()->subDays(30))
                                   ->orderBy('created_at')
                                   ->get();

            if ($records->isEmpty()) {
                continue;
            }

            $avgStats = HealthRecord::getAverageForPatient($patientId, 7);
            $status = $this->getBloodPressureStatus($avgStats?->avg_sistolik ?? 0, $avgStats?->avg_diastolik ?? 0);

            // Count by status
            $statusCounts = [
                'normal' => $records->filter(fn($r) => $r->sistolik < 120 && $r->diastolik < 80)->count(),
                'prehypertension' => $records->filter(fn($r) => ($r->sistolik >= 120 || $r->diastolik >= 80) && $r->sistolik < 140 && $r->diastolik < 90)->count(),
                'stage1' => $records->filter(fn($r) => ($r->sistolik >= 140 || $r->diastolik >= 90) && $r->sistolik < 160 && $r->diastolik < 100)->count(),
                'stage2' => $records->filter(fn($r) => $r->sistolik >= 160 || $r->diastolik >= 100)->count(),
            ];

            $trends[$patientId] = [
                'current_status' => $status,
                'average_sistolik' => round($avgStats?->avg_sistolik ?? 0, 2),
                'average_diastolik' => round($avgStats?->avg_diastolik ?? 0, 2),
                'status_distribution' => $statusCounts,
                'total_records' => $records->count(),
            ];
        }

        return $trends;
    }

    /**
     * Get blood pressure status
     */
    private function getBloodPressureStatus($sistolik, $diastolik): string
    {
        if ($sistolik >= 160 || $diastolik >= 100) {
            return 'Hipertensi Stage 2';
        } elseif ($sistolik >= 140 || $diastolik >= 90) {
            return 'Hipertensi Stage 1';
        } elseif ($sistolik >= 120 || $diastolik >= 80) {
            return 'Prehipertensi';
        } else {
            return 'Normal';
        }
    }

    /**
     * View patient feedback details
     */
    public function patientFeedback($patientId): View
    {
        $doctor = Auth::user();
        $patient = User::findOrFail($patientId);

        // Verify doctor has access to this patient
        if (!$doctor->patients()->where('patient_id', $patientId)->exists()) {
            abort(403);
        }

        $feedbacks = Feedback::where('receiver_id', $patientId)
                             ->where('sender_id', '!=', $doctor->id)
                             ->latest('created_at')
                             ->paginate(10);

        // Mark as read
        Feedback::where('receiver_id', $patientId)
               ->where('sender_id', '!=', $doctor->id)
               ->where('is_read', false)
               ->update(['is_read' => true, 'read_at' => now()]);

        return view('analytics.patient-feedback', [
            'patient' => $patient,
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * Get engagement trends (JSON for charts)
     */
    public function engagementTrends()
    {
        $doctor = Auth::user();
        $patients = $doctor->patients()->get();

        $data = [];
        foreach ($patients as $patient) {
            $entries = HealthRecord::where('patient_id', $patient->id)
                                   ->where('created_at', '>=', now()->subDays(30))
                                   ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                   ->groupBy('date')
                                   ->get();

            $data[$patient->name] = $entries->map(fn($e) => $e->count)->toArray();
        }

        return response()->json($data);
    }
}
