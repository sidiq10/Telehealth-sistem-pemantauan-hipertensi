@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Analytics Dashboard</h1>
            <p class="text-gray-600">Monitor your patients' engagement and feedback</p>
        </div>

        <!-- Feedback Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl mb-2">💬</div>
                <p class="text-gray-600 text-sm">Total Feedback</p>
                <p class="text-3xl font-bold text-blue-600">{{ $feedbackStats['total'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl mb-2">⭐</div>
                <p class="text-gray-600 text-sm">Average Rating</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $feedbackStats['average_rating'] }}/5</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl mb-2">📊</div>
                <p class="text-gray-600 text-sm">Rated Feedback</p>
                <p class="text-3xl font-bold text-green-600">{{ $feedbackStats['rated_feedbacks'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl mb-2">🔐</div>
                <p class="text-gray-600 text-sm">Anonymous</p>
                <p class="text-3xl font-bold text-purple-600">{{ $feedbackStats['anonymous_feedbacks'] }}</p>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Rating Distribution</h2>
            <div class="grid grid-cols-5 gap-2">
                @for($i = 5; $i >= 1; $i--)
                    <div class="text-center">
                        <p class="text-sm font-semibold mb-2">{{ $i }}⭐</p>
                        <div class="bg-gray-200 rounded h-32 flex items-end justify-center relative">
                            <div class="bg-blue-500 w-full rounded-t" 
                                 style="height: {{ $feedbackStats['rating_distribution'][$i] > 0 ? ($feedbackStats['rating_distribution'][$i] / max(1, collect($feedbackStats['rating_distribution'])->max())) * 100 : 0 }}%">
                            </div>
                            <span class="absolute bottom-2 text-xs font-bold">{{ $feedbackStats['rating_distribution'][$i] }}</span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Patient Engagement -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Patient Engagement</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Patient</th>
                            <th class="px-4 py-2 text-center">Data Entries</th>
                            <th class="px-4 py-2 text-center">This Month</th>
                            <th class="px-4 py-2 text-center">Points</th>
                            <th class="px-4 py-2 text-center">Streak</th>
                            <th class="px-4 py-2 text-center">Badges</th>
                            <th class="px-4 py-2 text-center">Last Entry</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagementStats as $patientId => $stats)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 font-semibold">{{ $stats['name'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $stats['total_entries'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $stats['entries_this_month'] }}</td>
                                <td class="px-4 py-2 text-center font-bold text-blue-600">{{ $stats['points'] }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($stats['streak_days'] > 0)
                                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-bold">
                                            🔥 {{ $stats['streak_days'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $stats['badges_count'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center text-xs">
                                    {{ $stats['last_entry']?->format('d M Y') ?? 'Never' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('analytics.patient-feedback', $patientId) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        View Feedback
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                    No patient data available yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Health Trends -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Health Status Trends (Last 30 Days)</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($healthTrends as $patientId => $trend)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold mb-3">{{ $engagementStats[$patientId]['name'] ?? 'Patient' }}</h3>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Status: <strong>{{ $trend['current_status'] }}</strong></p>
                            <p class="text-sm text-gray-600">Avg BP: {{ $trend['average_sistolik'] }}/{{ $trend['average_diastolik'] }} mmHg</p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm">🟢 Normal</span>
                                <div class="flex-1 mx-2 bg-gray-200 rounded h-2">
                                    <div class="bg-green-500 h-2 rounded" style="width: {{ ($trend['status_distribution']['normal'] / max(1, $trend['total_records'])) * 100 }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $trend['status_distribution']['normal'] }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm">🟡 Prehypertension</span>
                                <div class="flex-1 mx-2 bg-gray-200 rounded h-2">
                                    <div class="bg-yellow-500 h-2 rounded" style="width: {{ ($trend['status_distribution']['prehypertension'] / max(1, $trend['total_records'])) * 100 }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $trend['status_distribution']['prehypertension'] }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm">🟠 Stage 1</span>
                                <div class="flex-1 mx-2 bg-gray-200 rounded h-2">
                                    <div class="bg-orange-500 h-2 rounded" style="width: {{ ($trend['status_distribution']['stage1'] / max(1, $trend['total_records'])) * 100 }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $trend['status_distribution']['stage1'] }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm">🔴 Stage 2</span>
                                <div class="flex-1 mx-2 bg-gray-200 rounded h-2">
                                    <div class="bg-red-500 h-2 rounded" style="width: {{ ($trend['status_distribution']['stage2'] / max(1, $trend['total_records'])) * 100 }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $trend['status_distribution']['stage2'] }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-4">
                        No health data available for trend analysis.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
