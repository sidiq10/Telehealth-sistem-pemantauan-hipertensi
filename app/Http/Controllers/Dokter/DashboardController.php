<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard dokter
     */
    public function index(): View
    {
        $dokter = auth()->user();
        
        // Total pasien
        $totalPasien = $dokter->patients()->count();
        
        // Get all patients with their latest health records
        $allPasien = $dokter->patients()->with('healthRecords')->get();
        
        // Pasien dengan status warning (sistolik >= 140 atau diastolik >= 90)
        $warningPasien = 0;
        foreach ($allPasien as $pasien) {
            $latestRecord = $pasien->healthRecords()->latest()->first();
            if ($latestRecord && ($latestRecord->sistolik >= 140 || $latestRecord->diastolik >= 90)) {
                $warningPasien++;
            }
        }
        
        // Pesan yang belum dibaca
        $unreadMessages = $dokter->receivedFeedbacks()->where('is_read', false)->count();

        // Daftar pasien dengan latest record - paginated
        $pasien = $dokter->patients()
                        ->with('healthRecords')
                        ->paginate(10);

        return view('dokter.dashboard', [
            'totalPasien' => $totalPasien,
            'warningPasien' => $warningPasien,
            'unreadMessages' => $unreadMessages,
            'pasien' => $pasien,
        ]);
    }

    /**
     * Show pasien detail
     */
    public function detailPasien(User $pasien): View
    {
        // Check authorization: dokter hanya bisa lihat pasiennya sendiri
        $this->authorize('viewPasien', [$pasien, auth()->user()]);

        $days = request('days', 7);
        
        // Health records
        $records = $pasien->healthRecords()
                         ->lastDays($days)
                         ->orderBy('created_at')
                         ->paginate(10);

        // Stats
        $stats = HealthRecord::getStatsForPatient($pasien->id, $days);
        
        // Latest record
        $latestRecord = $pasien->healthRecords()->latest()->first();
        
        // Feedbacks dengan pasien ini
        $feedbacks = Feedback::getConversation(auth()->id(), $pasien->id);

        return view('dokter.detail-pasien', [
            'pasien' => $pasien,
            'records' => $records,
            'stats' => $stats,
            'latestRecord' => $latestRecord,
            'feedbacks' => $feedbacks,
            'days' => $days,
        ]);
    }

    /**
     * Send feedback ke pasien
     */
    public function sendFeedback(Request $request, User $pasien): RedirectResponse
    {
        // Check authorization
        $this->authorize('viewPasien', [$pasien, auth()->user()]);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        auth()->user()->sentFeedbacks()->create([
            'receiver_id' => $pasien->id,
            'message' => $validated['message'],
        ]);

        return redirect()->route('dokter.detail-pasien', $pasien)
                       ->with('success', 'Feedback berhasil dikirim!');
    }

    /**
     * Search pasien
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        $dokter = auth()->user();

        if ($query) {
            $pasien = $dokter->patients()
                            ->where('name', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%")
                            ->paginate(10);
        } else {
            $pasien = $dokter->patients()->paginate(10);
        }

        return view('dokter.search', [
            'pasien' => $pasien,
            'query' => $query,
        ]);
    }
}
