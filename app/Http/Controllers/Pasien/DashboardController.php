<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard pasien
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Data tekanan terakhir
        $latestRecord = $user->healthRecords()->latest()->first();
        
        // Rata-rata 7 hari terakhir
        $avgStats = HealthRecord::getAverageForPatient($user->id, 7);
        
        // Riwayat terbaru (5 data)
        $recentRecords = $user->healthRecords()->latest()->take(5)->get();
        
        // Notifikasi feedback yang belum dibaca
        $unreadFeedbacks = $user->receivedFeedbacks()->where('is_read', false)->count();
        
        // Gamification data
        $gamificationService = app(\App\Services\GamificationService::class);
        $gamificationProgress = $gamificationService->getProgress($user);

        return view('pasien.dashboard', [
            'latestRecord' => $latestRecord,
            'avgStats' => $avgStats,
            'recentRecords' => $recentRecords,
            'unreadFeedbacks' => $unreadFeedbacks,
            'gamification' => $gamificationProgress,
        ]);
    }

    /**
     * Show form input data tensi
     */
    public function createRecord(): View
    {
        return view('pasien.input-tensi');
    }

    /**
     * Store health record
     */
    public function storeRecord(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sistolik' => ['required', 'integer', 'min:50', 'max:250'],
            'diastolik' => ['required', 'integer', 'min:30', 'max:150'],
            'denyut_nadi' => ['required', 'integer', 'min:30', 'max:200'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        auth()->user()->healthRecords()->create($validated);

        return redirect()->route('pasien.dashboard')
                       ->with('success', 'Data tensi berhasil disimpan!');
    }

    /**
     * Show riwayat data tensi
     */
    public function riwayat(): View
    {
        $records = auth()->user()->healthRecords()
                        ->latest()
                        ->paginate(10);

        return view('pasien.riwayat', ['records' => $records]);
    }

    /**
     * Show grafik tren
     */
    public function grafik(Request $request): View
    {
        $days = $request->get('days', 7);
        $user = auth()->user();
        
        $records = $user->healthRecords()
                       ->lastDays($days)
                       ->orderBy('created_at')
                       ->get();

        $stats = HealthRecord::getStatsForPatient($user->id, $days);

        return view('pasien.grafik', [
            'records' => $records,
            'stats' => $stats,
            'days' => $days,
        ]);
    }

    /**
     * Show feedback/chat
     */
    public function feedback(): View
    {
        $user = auth()->user();
        
        // Ambil semua dokter yang menangani pasien ini
        $doctors = $user->doctors()->get();
        
        // Ambil feedback dari dokter
        $feedbacks = $user->receivedFeedbacks()
                         ->with('sender')
                         ->latest()
                         ->paginate(15);

        // Mark as read
        $user->receivedFeedbacks()->where('is_read', false)->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return view('pasien.feedback', [
            'doctors' => $doctors,
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * Send reply feedback
     */
    public function sendReply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        auth()->user()->sentFeedbacks()->create([
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('pasien.feedback')
                       ->with('success', 'Pesan berhasil dikirim!');
    }
}
