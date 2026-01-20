<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    Halo, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-gray-600 mt-2">Pantau kesehatan Anda dengan TeleHealth</p>
            </div>

            <!-- ⭐ GAMIFICATION SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Points Card -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $gamification['points'] }} Poin</h3>
                            <p class="text-purple-100">Total Points</p>
                        </div>
                        <div class="text-5xl">⭐</div>
                    </div>
                    
                    <div class="mt-4 bg-white bg-opacity-20 rounded p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold">Milestone Berikutnya</span>
                            <span class="text-sm">{{ $gamification['points_to_next_milestone'] }} poin lagi</span>
                        </div>
                        <div class="w-full bg-white bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full transition-all" 
                                 style="width: {{ min($gamification['points'] / max(1, $gamification['points'] + $gamification['points_to_next_milestone']), 1) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Streak Card -->
                <div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-lg p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-4xl font-bold">{{ $gamification['streak_days'] }}</h3>
                            <p class="text-orange-100 text-lg">Hari Streak</p>
                            <p class="text-orange-100 text-sm mt-2">Teruskan semangat! 🔥</p>
                        </div>
                        <div class="text-6xl">🔥</div>
                    </div>
                    
                    <div class="mt-4 text-sm">
                        <p>Terakhir input: <strong>{{ auth()->user()->last_data_entry_date?->format('d M Y') ?? 'Belum pernah' }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- Badges Section -->
            @if($gamification['badges']->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">🏆 Badge yang Dikumpulkan ({{ $gamification['badges_count'] }})</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($gamification['badges'] as $badge)
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-4 text-center border-2 border-yellow-300">
                            <div class="text-4xl mb-2">{{ $badge->icon_url ?? '🏅' }}</div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $badge->name }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $badge->description }}</p>
                            <div class="mt-2 text-xs text-green-600 font-semibold">✓ Terbuka</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Next Badge Challenge -->
            @if($gamification['next_badge'])
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">🎯 Badge Berikutnya</h3>
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $gamification['next_badge']['name'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $gamification['next_badge']['description'] }}</p>
                        </div>
                        <div class="text-3xl">🏆</div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold">Progress</span>
                            <span class="text-sm text-gray-600">{{ $gamification['next_badge']['progress'] }}/{{ $gamification['next_badge']['requirement'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all" 
                                 style="width: {{ min($gamification['next_badge']['percentage'], 100) }}%"></div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        🎯 {{ round(100 - $gamification['next_badge']['percentage']) }}% lagi sampai dapat badge ini!
                    </p>
                </div>
            </div>
            @endif

            <!-- Status Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Latest Reading -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembacaan Terakhir</h3>
                    @if($latestRecord)
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Sistolik:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $latestRecord->sistolik }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Diastolik:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $latestRecord->diastolik }}</span>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <span class="text-sm text-gray-500">{{ $latestRecord->formatted_date }}</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-lg">{{ $latestRecord->status_emoji }} {{ $latestRecord->status }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada data. Mulai input sekarang!</p>
                    @endif
                </div>

                <!-- Card 2: Average Stats -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rata-Rata 7 Hari</h3>
                    @if($avgStats)
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Sistolik:</span>
                                <span class="text-2xl font-bold text-green-600">{{ round($avgStats->avg_sistolik) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Diastolik:</span>
                                <span class="text-2xl font-bold text-green-600">{{ round($avgStats->avg_diastolik) }}</span>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                Rata-rata data yang Anda catat
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">Data belum cukup</p>
                    @endif
                </div>

                <!-- Card 3: Notifications -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesan Dokter</h3>
                    @if($unreadFeedbacks > 0)
                        <div class="space-y-2">
                            <p class="text-3xl font-bold text-purple-600">{{ $unreadFeedbacks }}</p>
                            <p class="text-gray-600">Pesan baru dari dokter</p>
                            <a href="{{ route('pasien.feedback') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-800 font-medium">
                                Baca sekarang →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500">Tidak ada pesan baru</p>
                    @endif
                </div>
            </div>

            <!-- ✨ RECOMMENDATIONS SECTION -->
            @if($latestRecord && $latestRecord->recommendations)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow p-6 mb-8 border-l-4 border-blue-500">
                <h3 class="text-xl font-bold text-gray-900 mb-4">💡 Rekomendasi Kesehatan Anda</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($latestRecord->recommendations['recommendations'] as $rec)
                    <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" 
                         style="border-color: {{ $rec['severity'] === 'critical' ? '#dc2626' : ($rec['severity'] === 'high' ? '#f59e0b' : ($rec['severity'] === 'medium' ? '#3b82f6' : '#10b981')) }}">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-bold text-gray-800">{{ $rec['title'] }}</h4>
                            <span class="text-2xl">
                                @if($rec['type'] === 'diet')
                                    🍽️
                                @elseif($rec['type'] === 'exercise')
                                    💪
                                @elseif($rec['type'] === 'lifestyle')
                                    🧘
                                @elseif($rec['type'] === 'health')
                                    ❤️
                                @elseif($rec['type'] === 'trend')
                                    📈
                                @else
                                    🎯
                                @endif
                            </span>
                        </div>
                        <p class="text-sm text-gray-700">{{ $rec['description'] }}</p>
                        <div class="mt-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                Prioritas {{ $rec['priority'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Records Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Terbaru</h3>
                </div>
                @if($recentRecords->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Sistolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Diastolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">DN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($recentRecords as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $record->sistolik }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $record->diastolik }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->denyut_nadi }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="text-lg">{{ $record->status_emoji }} {{ $record->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t">
                        <a href="{{ route('pasien.riwayat') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Semua Data →
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        Belum ada data. Mulai input data tensi Anda sekarang!
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
