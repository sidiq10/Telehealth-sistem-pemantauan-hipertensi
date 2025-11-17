<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grafik Tren Tensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Selector -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex gap-4 flex-wrap">
                    <a href="{{ route('pasien.grafik', ['days' => 7]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition
                           {{ $days == 7 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        7 Hari
                    </a>
                    <a href="{{ route('pasien.grafik', ['days' => 14]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition
                           {{ $days == 14 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        14 Hari
                    </a>
                    <a href="{{ route('pasien.grafik', ['days' => 30]) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition
                           {{ $days == 30 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        30 Hari
                    </a>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Tren Tekanan Darah ({{ $days }} Hari)</h3>
                @if($records->count())
                    <div class="relative h-96">
                        <canvas id="trendChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <p>Belum cukup data untuk menampilkan grafik. Silakan input lebih banyak data.</p>
                    </div>
                @endif
            </div>

            <!-- Statistics -->
            @if($stats)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sistolik Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Statistik Sistolik</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Rata-rata:</span>
                            <span class="text-2xl font-bold text-blue-600">{{ round($stats->avg_sistolik, 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Minimal:</span>
                            <span class="text-xl font-semibold text-green-600">{{ $stats->min_sistolik }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Maksimal:</span>
                            <span class="text-xl font-semibold text-red-600">{{ $stats->max_sistolik }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Std Dev:</span>
                            <span class="text-lg font-semibold">{{ round($stats->stddev_sistolik, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Diastolik Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Statistik Diastolik</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Rata-rata:</span>
                            <span class="text-2xl font-bold text-blue-600">{{ round($stats->avg_diastolik, 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Minimal:</span>
                            <span class="text-xl font-semibold text-green-600">{{ $stats->min_diastolik }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Maksimal:</span>
                            <span class="text-xl font-semibold text-red-600">{{ $stats->max_diastolik }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Std Dev:</span>
                            <span class="text-lg font-semibold">{{ round($stats->stddev_diastolik, 2) }}</span>
                        </div>
                    </div>
                </div>
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

    <!-- Chart.js Script -->
    @if($records->count())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dates = {!! json_encode($records->pluck('created_at')->map(fn($d) => $d->format('d M'))) !!};
        const sistolikData = {!! json_encode($records->pluck('sistolik')) !!};
        const diastolikData = {!! json_encode($records->pluck('diastolik')) !!};

        const ctx = document.getElementById('trendChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Sistolik (mmHg)',
                        data: sistolikData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Diastolik (mmHg)',
                        data: diastolikData,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#EF4444',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' mmHg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        beginAtZero: false,
                        min: 50,
                        max: 200,
                        title: {
                            display: true,
                            text: 'Tekanan Darah (mmHg)',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>
    @endif
</x-app-layout>
