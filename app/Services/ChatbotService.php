<?php

namespace App\Services;

use App\Models\User;
use App\Models\ChatMessage;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    protected $openaiClient;
    protected $useAI;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('openai.api_key');
        $this->useAI = !empty($this->apiKey) && strpos($this->apiKey, 'sk-proj-') === 0;
        
        if ($this->useAI) {
            try {
                Log::info('Initializing OpenAI client');
                // Correct way to initialize OpenAI client
                $this->openaiClient = (new \OpenAI\Factory())
                    ->withApiKey($this->apiKey)
                    ->make();
                Log::info('OpenAI client initialized successfully');
            } catch (\Exception $e) {
                Log::error('OpenAI initialization failed: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ]);
                $this->useAI = false;
            }
        } else {
            Log::warning('OpenAI API key not configured properly');
        }
    }

    public function generateResponse(User $patient, string $message): string
    {
        try {
            if ($this->useAI) {
                Log::info('Attempting to use ChatGPT for response');
                return $this->generateAIResponse($patient, $message);
            }
        } catch (\Exception $e) {
            Log::error('ChatGPT API error, falling back to local response: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Fallback to local responses
        Log::info('Using local response generation');
        return $this->generateLocalResponse($patient, $message);
    }

    private function generateAIResponse(User $patient, string $userMessage): string
    {
        // Get patient's health context
        $healthContext = $this->getHealthContext($patient);
        
        $systemPrompt = "Anda adalah asisten kesehatan yang peduli dan membantu dari aplikasi TeleHealth. " .
                       "Anda membantu pasien memantau kesehatan mereka dengan memberikan saran yang akurat berdasarkan data kesehatan mereka. " .
                       "Berikan respons dalam bahasa Indonesia yang ramah dan mudah dipahami. " .
                       "Selalu prioritaskan keselamatan pasien dan sarankan untuk berkonsultasi dengan dokter jika diperlukan.\n\n" .
                       "Data kesehatan pasien:\n" . $healthContext;

        try {
            Log::info('Sending request to OpenAI API', [
                'model' => config('openai.model'),
                'user_message' => substr($userMessage, 0, 100),
            ]);
            
            $response = $this->openaiClient->chat()->create([
                'model' => config('openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage,
                    ],
                ],
                'max_tokens' => config('openai.max_tokens', 500),
                'temperature' => config('openai.temperature', 0.7),
            ]);

            $content = $response->choices[0]->message->content;
            Log::info('Successfully received response from OpenAI');
            return $content;
        } catch (\Exception $e) {
            Log::error('ChatGPT API error: ' . $e->getMessage(), [
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getHealthContext(User $patient): string
    {
        $latestRecord = $patient->healthRecords()->latest()->first();
        $avgStats = $patient->healthRecords()->whereBetween('created_at', [
            now()->subDays(7),
            now(),
        ])->selectRaw('AVG(sistolik) as avg_sistolik, AVG(diastolik) as avg_diastolik')->first();

        $context = "Nama: " . $patient->name . "\n";
        
        if ($latestRecord) {
            $context .= "Pembacaan Terakhir:\n";
            $context .= "- Sistolik: " . $latestRecord->sistolik . " mmHg\n";
            $context .= "- Diastolik: " . $latestRecord->diastolik . " mmHg\n";
            $context .= "- Detak Nadi: " . $latestRecord->denyut_nadi . " bpm\n";
            $context .= "- Status: " . $latestRecord->status . "\n";
            $context .= "- Waktu: " . $latestRecord->created_at->format('d M Y H:i') . "\n";
        } else {
            $context .= "Belum ada data kesehatan tercatat.\n";
        }

        if ($avgStats) {
            $context .= "Rata-rata 7 Hari:\n";
            $context .= "- Sistolik: " . round($avgStats->avg_sistolik) . " mmHg\n";
            $context .= "- Diastolik: " . round($avgStats->avg_diastolik) . " mmHg\n";
        }

        return $context;
    }

    private function generateLocalResponse(User $patient, string $message): string
    {
        $lowerMessage = strtolower($message);
        
        // Greetings
        if ($this->matchesKeyword($lowerMessage, ['halo', 'hi', 'hello', 'pagi', 'siang', 'malam'])) {
            return "Halo " . $patient->name . "! 👋 Saya asisten kesehatan Anda. Bagaimana kabar kesehatan Anda hari ini? Apakah ada yang ingin dibicarakan?";
        }

        // Health status queries
        if ($this->matchesKeyword($lowerMessage, ['tensi', 'tekanan darah', 'bp', 'sistolik', 'diastolik'])) {
            return $this->getHealthStatus($patient);
        }

        // Heart rate queries
        if ($this->matchesKeyword($lowerMessage, ['detak jantung', 'denyut nadi', 'heart rate', 'bpm'])) {
            $latestRecord = $patient->healthRecords()->latest()->first();
            if ($latestRecord) {
                $status = $latestRecord->denyut_nadi >= 60 && $latestRecord->denyut_nadi <= 100 ? 'normal' : 'perlu perhatian';
                return "Detak jantung terakhir Anda adalah **{$latestRecord->denyut_nadi} bpm** ($status). Pertahankan aktivitas fisik teratur untuk kesehatan jantung yang optimal! 💓";
            }
            return "Belum ada data detak jantung. Silakan input data kesehatan Anda terlebih dahulu.";
        }

        // Recommendations
        if ($this->matchesKeyword($lowerMessage, ['rekomendasi', 'saran', 'tips', 'nasihat', 'apa yang harus'])) {
            return $this->getRecommendations($patient);
        }

        // History queries
        if ($this->matchesKeyword($lowerMessage, ['riwayat', 'history', 'berapa kali', 'sudah berapa'])) {
            $count = $patient->healthRecords()->count();
            return "Anda telah mencatat data kesehatan sebanyak **$count kali**. Terus semangat memantau kesehatan Anda! 📊";
        }

        // Appointment/Consultation
        if ($this->matchesKeyword($lowerMessage, ['dokter', 'konsultasi', 'appointment', 'jadwal', 'bertemu'])) {
            return "Untuk berkonsultasi dengan dokter, silakan klik menu 'Chat Dokter' di navbar. Dokter akan segera merespons pertanyaan Anda! 👨‍⚕️";
        }

        // Streak queries
        if ($this->matchesKeyword($lowerMessage, ['streak', 'hari berturut', 'konsisten'])) {
            $gamificationService = new GamificationService();
            $gamification = $gamificationService->getUserGamification($patient);
            return "Anda memiliki streak **{$gamification['streak_days']} hari** berturut-turut! Luar biasa! Jangan putus semangatnya! 🔥";
        }

        // Points queries
        if ($this->matchesKeyword($lowerMessage, ['poin', 'points', 'skor', 'score'])) {
            $gamificationService = new GamificationService();
            $gamification = $gamificationService->getUserGamification($patient);
            return "Anda telah mengumpulkan **{$gamification['points']} poin**! Setiap input data kesehatan memberikan poin dan membawa Anda lebih dekat ke badge berikutnya. Terus semangat! ⭐";
        }

        // Badge queries
        if ($this->matchesKeyword($lowerMessage, ['badge', 'penghargaan', 'achievement', 'prestasi'])) {
            $gamificationService = new GamificationService();
            $gamification = $gamificationService->getUserGamification($patient);
            $badgeCount = $gamification['badges_count'];
            return "Anda telah membuka **$badgeCount badge**! Silakan cek halaman dashboard untuk melihat semua badge yang Anda kumpulkan. Kumpulkan lebih banyak dengan terus memantau kesehatan! 🏆";
        }

        // Help/Menu
        if ($this->matchesKeyword($lowerMessage, ['help', 'bantuan', 'apa saja', 'bisa apa', 'fitur'])) {
            return "Saya bisa membantu Anda dengan:\n" .
                   "✓ Menampilkan status kesehatan terkini\n" .
                   "✓ Memberikan rekomendasi kesehatan\n" .
                   "✓ Menunjukkan riwayat data Anda\n" .
                   "✓ Menjelaskan badge dan poin\n" .
                   "✓ Membantu Anda berkonsultasi dengan dokter\n\n" .
                   "Apa yang ingin Anda ketahui?";
        }

        // Default response
        return $this->getDefaultResponse();
    }

    private function getHealthStatus(User $patient): string
    {
        $latestRecord = $patient->healthRecords()->latest()->first();
        
        if (!$latestRecord) {
            return "Belum ada data kesehatan tercatat. Segera input data tensi Anda di menu 'Input Data Tensi' untuk memulai pemantauan kesehatan! 📋";
        }

        $status = $latestRecord->status;
        $emoji = $latestRecord->status_emoji;
        
        return "Status kesehatan terakhir Anda:\n\n" .
               "📊 **Sistolik**: {$latestRecord->sistolik} mmHg\n" .
               "📊 **Diastolik**: {$latestRecord->diastolik} mmHg\n" .
               "💓 **Detak Nadi**: {$latestRecord->denyut_nadi} bpm\n" .
               "Status: $emoji **$status**\n\n" .
               "Waktu: " . $latestRecord->created_at->format('d M Y, H:i');
    }

    private function getRecommendations(User $patient): string
    {
        $latestRecord = $patient->healthRecords()->latest()->first();
        
        if (!$latestRecord || !$latestRecord->recommendations) {
            return "Belum ada rekomendasi tersedia. Input data kesehatan Anda untuk mendapatkan rekomendasi yang dipersonalisasi! 💡";
        }

        $recommendations = $latestRecord->recommendations['recommendations'] ?? [];
        
        if (empty($recommendations)) {
            return "Kesehatan Anda dalam kondisi baik! Terus jaga pola hidup sehat dan rutin memantau tekanan darah Anda. 👍";
        }

        $result = "📋 **Rekomendasi untuk Anda:**\n\n";
        foreach (array_slice($recommendations, 0, 3) as $rec) {
            $result .= "• **{$rec['title']}**: {$rec['description']}\n";
        }
        
        return $result;
    }

    private function getDefaultResponse(): string
    {
        $responses = [
            "Terima kasih atas pertanyaan Anda! Saya di sini untuk membantu. Apakah ada yang ingin Anda tanyakan tentang kesehatan Anda? 😊",
            "Saya kurang memahami pertanyaan Anda. Bisa rincikan lebih detail? Atau ketik 'help' untuk melihat apa saja yang bisa saya bantu. 🤔",
            "Interesting! Apakah Anda ingin bertanya lebih detail tentang kesehatan Anda? 💙",
            "Gotcha! Ada yang lain yang ingin Anda tanyakan? Saya siap membantu monitoring kesehatan Anda! 📊",
        ];
        
        return $responses[array_rand($responses)];
    }

    private function matchesKeyword(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (strpos($message, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    }

    public function saveChatMessage(User $patient, string $userMessage, string $botResponse, string $category = 'general'): ChatMessage
    {
        return ChatMessage::create([
            'patient_id' => $patient->id,
            'message' => $userMessage,
            'response' => $botResponse,
            'type' => 'bot',
            'category' => $category,
        ]);
    }

    public function getChatHistory(User $patient, int $limit = 50)
    {
        return ChatMessage::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }
}
