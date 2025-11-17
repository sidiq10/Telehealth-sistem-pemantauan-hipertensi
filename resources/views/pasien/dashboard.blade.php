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

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('pasien.input-tensi') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Input Data Tensi
                    </a>
                    <a href="{{ route('pasien.riwayat') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        📊 Lihat Riwayat
                    </a>
                    <a href="{{ route('pasien.grafik') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        📈 Lihat Grafik
                    </a>
                    <a href="{{ route('pasien.feedback') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                        💬 Chat Dokter
                    </a>
                </div>
            </div>

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
