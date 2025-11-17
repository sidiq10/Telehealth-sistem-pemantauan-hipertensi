<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat dengan Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Feedback Messages -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Pesan dari Dokter</h3>
                </div>

                @if($feedbacks->count())
                    <div class="divide-y max-h-96 overflow-y-auto">
                        @foreach($feedbacks as $feedback)
                        <div class="p-6 @if(!$feedback->is_read) bg-blue-50 @endif">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    @if($feedback->sender_id === auth()->id())
                                        <span class="text-sm font-semibold text-green-700">Anda</span>
                                    @else
                                        <span class="text-sm font-semibold text-blue-700">{{ $feedback->sender->name }}</span>
                                        @if($feedback->sender->role === 'dokter')
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">Dokter</span>
                                        @endif
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-800 text-sm leading-relaxed">{{ $feedback->message }}</p>
                            @if($feedback->is_read && $feedback->sender_id !== auth()->id())
                                <span class="text-xs text-gray-500 mt-2">✓ Dibaca</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t">
                        {{ $feedbacks->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p>Belum ada pesan dari dokter</p>
                    </div>
                @endif
            </div>

            <!-- Send Reply Form -->
            @if($doctors->count())
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Kirim Pesan</h3>
                </div>

                <form method="POST" action="{{ route('pasien.send-reply') }}" class="p-6 space-y-4">
                    @csrf

                    <!-- Doctor Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kirim ke Dokter</label>
                        <select name="receiver_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea 
                            name="message" 
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="5"
                            placeholder="Tulis pertanyaan atau masalah Anda..."
                            required
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Kirim Pesan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <p class="text-blue-800">Belum ada dokter yang ditugaskan. Hubungi admin untuk menambahkan dokter.</p>
            </div>
            @endif

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('pasien.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
