<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\ChatbotController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect ke dashboard sesuai role jika sudah login
    if (auth()->check()) {
        return auth()->user()->role === 'pasien' 
            ? redirect()->route('pasien.dashboard')
            : redirect()->route('dokter.dashboard');
    }
    return view('welcome');
})->name('home');

// ===== PASIEN ROUTES =====
Route::middleware(['auth', 'verified', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('dashboard');
    
    // Input Data Tensi
    Route::get('/input-tensi', [PasienDashboardController::class, 'createRecord'])->name('input-tensi');
    Route::post('/input-tensi', [PasienDashboardController::class, 'storeRecord'])->name('store-tensi');
    
    // Riwayat
    Route::get('/riwayat', [PasienDashboardController::class, 'riwayat'])->name('riwayat');
    
    // Grafik
    Route::get('/grafik', [PasienDashboardController::class, 'grafik'])->name('grafik');
    
    // Feedback
    Route::get('/feedback', [PasienDashboardController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/reply', [PasienDashboardController::class, 'sendReply'])->name('send-reply');
    
    // Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::get('/chatbot/history', [ChatbotController::class, 'getHistory'])->name('chatbot.history');
    Route::post('/chatbot/clear', [ChatbotController::class, 'clear'])->name('chatbot.clear');
    Route::get('/chatbot/test-connection', [ChatbotController::class, 'testConnection'])->name('chatbot.test');
});

// ===== DOKTER ROUTES =====
Route::middleware(['auth', 'verified', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');
    
    // Detail Pasien
    Route::get('/pasien/{pasien}', [DokterDashboardController::class, 'detailPasien'])->name('detail-pasien');
    
    // Feedback
    Route::post('/pasien/{pasien}/feedback', [DokterDashboardController::class, 'sendFeedback'])->name('send-feedback');
    
    // Search
    Route::get('/search', [DokterDashboardController::class, 'search'])->name('search');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/patient/{patient}', [AnalyticsController::class, 'patientFeedback'])->name('analytics.patient');
    Route::get('/analytics/trends/engagement', [AnalyticsController::class, 'engagementTrends'])->name('analytics.trends');
});

// ===== PROFILE ROUTES =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
