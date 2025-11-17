<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pasien: {{ $pasien->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('dokter.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <!-- Patient Info -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Informasi Pasien</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Nama</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pasien->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Usia</p>
                        <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pasien->birthdate)->age ?? '-' }} tahun</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pasien->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Telepon</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pasien->phone ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Alamat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $pasien->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Latest Reading & Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Latest -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Pembacaan Terakhir</h4>
                    @if($latestRecord)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-gray-600">Sistolik:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $latestRecord->sistolik }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-gray-600">Diastolik:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $latestRecord->diastolik }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-gray-600">Denyut Nadi:</span>
                                <span class="text-xl font-semibold">{{ $latestRecord->denyut_nadi }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status:</span>
                                <span class="text-lg">{{ $latestRecord->status_emoji }} {{ $latestRecord->status }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">{{ $latestRecord->formatted_date }}</p>
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada data</p>
                    @endif
                </div>

                <!-- Stats -->
                @if($stats)
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Statistik {{ $days }} Hari</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rata-rata Sistolik:</span>
                            <span class="font-semibold">{{ round($stats->avg_sistolik, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rata-rata Diastolik:</span>
                            <span class="font-semibold">{{ round($stats->avg_diastolik, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Data:</span>
                            <span class="font-semibold">{{ $stats->total_records }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Grafik -->
            @if($records->count())
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Tren ({{ $days }} Hari)</h3>
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('dokter.detail-pasien', [$pasien, 'days' => 7]) }}" 
                       class="px-3 py-1 rounded text-sm {{ $days == 7 ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">7H</a>
                    <a href="{{ route('dokter.detail-pasien', [$pasien, 'days' => 14]) }}" 
                       class="px-3 py-1 rounded text-sm {{ $days == 14 ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">14H</a>
                    <a href="{{ route('dokter.detail-pasien', [$pasien, 'days' => 30]) }}" 
                       class="px-3 py-1 rounded text-sm {{ $days == 30 ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">30H</a>
                </div>
                <div class="relative h-80">
                    <canvas id="pasienChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Health Records Table -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Data Tensi</h3>
                </div>
                @if($records->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Sistolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Diastolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">DN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($records as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $record->sistolik }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $record->diastolik }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $record->denyut_nadi }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="text-lg">{{ $record->status_emoji }} {{ $record->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t">
                        {{ $records->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        Belum ada data
                    </div>
                @endif
            </div>

            <!-- Feedback Form & History -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Feedback & Percakapan</h3>
                </div>

                <!-- Feedback History -->
                @if($feedbacks->count())
                <div class="p-6 border-b max-h-64 overflow-y-auto bg-gray-50">
                    <div class="space-y-4">
                        @foreach($feedbacks as $feedback)
                        <div class="bg-white p-4 rounded border-l-4 @if($feedback->sender_id === auth()->id()) border-blue-500 @else border-green-500 @endif">
                            <p class="text-sm font-semibold @if($feedback->sender_id === auth()->id()) text-blue-700 @else text-green-700 @endif">
                                @if($feedback->sender_id === auth()->id())
                                    Anda
                                @else
                                    {{ $feedback->sender->name }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-800 mt-1">{{ $feedback->message }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $feedback->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Send Feedback Form -->
                <form method="POST" action="{{ route('dokter.send-feedback', $pasien) }}" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kirim Feedback</label>
                        <textarea 
                            name="message" 
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="4"
                            placeholder="Masukkan rekomendasi, instruksi, atau feedback..."
                            required
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Kirim Feedback
                        </button>
                    </div>
                </form>
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

        const ctx = document.getElementById('pasienChart').getContext('2d');
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
                        pointRadius: 4,
                        pointBackgroundColor: '#3B82F6',
                    },
                    {
                        label: 'Diastolik (mmHg)',
                        data: diastolikData,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#EF4444',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 50,
                        max: 200
                    }
                }
            }
        });
    </script>
    @endif
</x-app-layout>
