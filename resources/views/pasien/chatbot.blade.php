<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('🤖 Chatbot Kesehatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Chat Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        🤖 Asisten Kesehatan TeleHealth
                    </h1>
                    <p class="text-blue-100 mt-1">Tanyakan apa saja tentang kesehatan Anda</p>
                </div>

                <!-- Chat Messages Container -->
                <div id="chatMessages" class="h-96 overflow-y-auto bg-gray-50 p-6 space-y-4">
                    @if($chatHistory->isEmpty())
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="text-5xl mb-4">👋</div>
                                <p class="text-gray-500 mb-2">Halo {{ auth()->user()->name }}!</p>
                                <p class="text-gray-400 text-sm">Mulai percakapan dengan bertanya tentang kesehatan Anda</p>
                            </div>
                        </div>
                    @else
                        @foreach($chatHistory as $msg)
                            <!-- User Message -->
                            <div class="flex justify-end mb-4">
                                <div class="bg-blue-600 text-white px-4 py-3 rounded-lg max-w-xs">
                                    <p class="text-sm">{{ $msg->message }}</p>
                                    <span class="text-xs text-blue-100 mt-1">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                            </div>

                            <!-- Bot Message -->
                            <div class="flex justify-start mb-4">
                                <div class="bg-white border border-gray-200 px-4 py-3 rounded-lg max-w-xs">
                                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $msg->response }}</p>
                                    <span class="text-xs text-gray-400 mt-1">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Input Form -->
                <div class="border-t border-gray-200 p-6 bg-gray-50">
                    <form id="chatForm" class="flex gap-3">
                        @csrf
                        <input 
                            type="text" 
                            id="messageInput" 
                            name="message"
                            placeholder="Ketik pertanyaan Anda..." 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autocomplete="off"
                        >
                        <button 
                            type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold"
                        >
                            Kirim
                        </button>
                    </form>

                    <!-- Quick Suggestions -->
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-2">💡 Coba tanyakan:</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="quick-btn text-xs bg-white border border-gray-300 px-3 py-1 rounded-full hover:bg-gray-100">
                                Bagaimana tensi saya?
                            </button>
                            <button type="button" class="quick-btn text-xs bg-white border border-gray-300 px-3 py-1 rounded-full hover:bg-gray-100">
                                Apa rekomendasi Anda?
                            </button>
                            <button type="button" class="quick-btn text-xs bg-white border border-gray-300 px-3 py-1 rounded-full hover:bg-gray-100">
                                Berapa poin saya?
                            </button>
                            <button type="button" class="quick-btn text-xs bg-white border border-gray-300 px-3 py-1 rounded-full hover:bg-gray-100">
                                Help
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="border-t border-gray-200 px-6 py-3 bg-white flex justify-between items-center">
                    <p class="text-xs text-gray-500">💬 Chat history disimpan otomatis</p>
                    <button 
                        type="button" 
                        id="clearBtn"
                        class="text-xs text-red-600 hover:text-red-800 font-medium"
                    >
                        Hapus Chat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatMessages = document.getElementById('chatMessages');
        const clearBtn = document.getElementById('clearBtn');
        const quickBtns = document.querySelectorAll('.quick-btn');

        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            
            if (!message) return;

            // Clear input
            messageInput.value = '';

            // Add user message to UI
            addMessageToUI(message, null, 'user');

            // Send to server
            try {
                const response = await fetch('{{ route("pasien.chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();
                
                if (data.success) {
                    addMessageToUI(null, data.message.bot_response, 'bot', data.message.created_at);
                }
            } catch (error) {
                console.error('Error:', error);
                addMessageToUI(null, 'Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        });

        // Add message to chat UI
        function addMessageToUI(userMsg, botMsg, type, time = null) {
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const timestamp = time || now;

            if (type === 'user' && userMsg) {
                const userDiv = document.createElement('div');
                userDiv.className = 'flex justify-end mb-4';
                userDiv.innerHTML = `
                    <div class="bg-blue-600 text-white px-4 py-3 rounded-lg max-w-xs">
                        <p class="text-sm">${escapeHtml(userMsg)}</p>
                        <span class="text-xs text-blue-100 mt-1">${timestamp}</span>
                    </div>
                `;
                chatMessages.appendChild(userDiv);
            }

            if (type === 'bot' && botMsg) {
                const botDiv = document.createElement('div');
                botDiv.className = 'flex justify-start mb-4';
                botDiv.innerHTML = `
                    <div class="bg-white border border-gray-200 px-4 py-3 rounded-lg max-w-xs">
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">${escapeHtml(botMsg)}</p>
                        <span class="text-xs text-gray-400 mt-1">${timestamp}</span>
                    </div>
                `;
                chatMessages.appendChild(botDiv);
            }

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Quick button suggestions
        quickBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                messageInput.value = btn.textContent;
                messageInput.focus();
            });
        });

        // Clear chat history
        clearBtn.addEventListener('click', async () => {
            if (!confirm('Yakin ingin menghapus semua chat?')) return;

            try {
                const response = await fetch('{{ route("pasien.chatbot.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                });

                const data = await response.json();
                
                if (data.success) {
                    chatMessages.innerHTML = `
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="text-5xl mb-4">👋</div>
                                <p class="text-gray-500 mb-2">Chat history telah dihapus</p>
                                <p class="text-gray-400 text-sm">Mulai percakapan baru dengan bot kami</p>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal menghapus chat history');
            }
        });

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-focus input
        messageInput.focus();
    </script>
</x-app-layout>
