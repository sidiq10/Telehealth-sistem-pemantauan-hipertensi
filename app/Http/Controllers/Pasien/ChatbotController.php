<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Display chatbot interface
     */
    public function index()
    {
        $patient = Auth::user();
        $chatHistory = $this->chatbotService->getChatHistory($patient);

        return view('pasien.chatbot', [
            'chatHistory' => $chatHistory,
        ]);
    }

    /**
     * Send message to chatbot and get response
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $patient = Auth::user();
        $userMessage = $validated['message'];

        // Generate bot response
        $botResponse = $this->chatbotService->generateResponse($patient, $userMessage);

        // Save chat message
        $chatMessage = $this->chatbotService->saveChatMessage(
            $patient,
            $userMessage,
            $botResponse
        );

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'user_message' => $userMessage,
                'bot_response' => $botResponse,
                'created_at' => $chatMessage->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Get chat history (for pagination/loading more)
     */
    public function getHistory(Request $request)
    {
        $patient = Auth::user();
        $limit = $request->get('limit', 50);

        $chatHistory = $this->chatbotService->getChatHistory($patient, $limit);

        return response()->json([
            'success' => true,
            'messages' => $chatHistory->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'user_message' => $msg->message,
                    'bot_response' => $msg->response,
                    'created_at' => $msg->created_at->format('H:i'),
                ];
            }),
        ]);
    }

    /**
     * Clear chat history
     */
    public function clear(Request $request)
    {
        $patient = Auth::user();
        ChatMessage::where('patient_id', $patient->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared',
        ]);
    }

    /**
     * Test OpenAI connection
     */
    public function testConnection(Request $request)
    {
        $apiKey = config('openai.api_key');
        $isConfigured = !empty($apiKey) && $apiKey !== 'sk-your-api-key-here';

        if (!$isConfigured) {
            return response()->json([
                'success' => false,
                'status' => 'not_configured',
                'message' => 'OpenAI API key is not configured',
            ]);
        }

        try {
            $client = \OpenAI::client($apiKey);
            
            // Simple test call
            $response = $client->chat()->create([
                'model' => config('openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Test connection. Reply with "OK" only.',
                    ],
                ],
                'max_tokens' => 10,
                'temperature' => 0.5,
            ]);

            return response()->json([
                'success' => true,
                'status' => 'connected',
                'message' => 'OpenAI API is working!',
                'response' => $response->choices[0]->message->content,
            ]);
        } catch (\Exception $e) {
            \Log::error('OpenAI test connection failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to connect to OpenAI',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
