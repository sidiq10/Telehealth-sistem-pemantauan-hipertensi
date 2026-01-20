<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics & Engagement Metrics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('dokter.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Total Pasien</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalPatients }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Total Feedback</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalFeedbacks }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Avg Rating</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $avgRating }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Engagement Score</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $engagementScore }}</p>
                </div>
            </div>

            <!-- Feedback Distribution -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Rating Distribution</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-gray-600 w-20">⭐ Excellent</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $feedbackDistribution['excellent'] > 0 ? ($feedbackDistribution['excellent'] / ($feedbackDistribution['excellent'] + $feedbackDistribution['good'] + $feedbackDistribution['neutral'] + $feedbackDistribution['poor']) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-12">{{ $feedbackDistribution['excellent'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-gray-600 w-20">👍 Good</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $feedbackDistribution['good'] > 0 ? ($feedbackDistribution['good'] / ($feedbackDistribution['excellent'] + $feedbackDistribution['good'] + $feedbackDistribution['neutral'] + $feedbackDistribution['poor']) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-12">{{ $feedbackDistribution['good'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-gray-600 w-20">👌 Neutral</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $feedbackDistribution['neutral'] > 0 ? ($feedbackDistribution['neutral'] / ($feedbackDistribution['excellent'] + $feedbackDistribution['good'] + $feedbackDistribution['neutral'] + $feedbackDistribution['poor']) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-12">{{ $feedbackDistribution['neutral'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-gray-600 w-20">👎 Poor</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $feedbackDistribution['poor'] > 0 ? ($feedbackDistribution['poor'] / ($feedbackDistribution['excellent'] + $feedbackDistribution['good'] + $feedbackDistribution['neutral'] + $feedbackDistribution['poor']) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-12">{{ $feedbackDistribution['poor'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🏥 Health Trends</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Normal</span>
                            <span class="text-lg font-bold text-green-600">{{ $healthTrends['Normal'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Prehipertensi</span>
                            <span class="text-lg font-bold text-yellow-600">{{ $healthTrends['Prehipertensi'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Hipertensi Stage 1</span>
                            <span class="text-lg font-bold text-orange-600">{{ $healthTrends['Hipertensi Stage 1'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Hipertensi Stage 2</span>
                            <span class="text-lg font-bold text-red-600">{{ $healthTrends['Hipertensi Stage 2'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Patients -->
            @if($warningPatients->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-bold text-red-900 mb-4">⚠️ Pasien dengan Warning Status</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-red-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Nama</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($warningPatients as $patient)
                            <tr class="hover:bg-red-100">
                                <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $patient->name }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $patient->healthRecords()->latest()->first()?->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-900">
                                    {{ $patient->healthRecords()->latest()->first()?->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 text-sm">
                                    <a href="{{ route('dokter.detail-pasien', $patient) }}" class="text-blue-600 hover:text-blue-800">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Patient Engagement Ranking -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🏆 Top Patient Engagement</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama Pasien</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Poin</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Data Entry</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Feedback</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Entry Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($patientEngagement->take(10) as $index => $patient)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                    @if($index == 0)
                                        🥇
                                    @elseif($index == 1)
                                        🥈
                                    @elseif($index == 2)
                                        🥉
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $patient['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800">
                                        {{ $patient['points'] }} ⭐
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $patient['entries'] }} entries</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $patient['feedbacks'] }} feedbacks</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @if($patient['lastEntry'])
                                        {{ $patient['lastEntry']->diffForHumans() }}
                                    @else
                                        Belum ada
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
