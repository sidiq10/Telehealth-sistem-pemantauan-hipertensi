<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    Halo, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-gray-600 mt-2">Pantau kondisi kesehatan pasien Anda</p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Patients -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Total Pasien</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalPasien }}</p>
                </div>

                <!-- Warning Patients -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Pasien dengan Warning</h3>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $warningPasien }}</p>
                </div>

                <!-- Unread Messages -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Pesan Baru</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $unreadMessages }}</p>
                </div>
            </div>

            <!-- Daftar Pasien -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Pasien Saya</h3>
                    </div>
                    <div class="flex gap-2">
                        <form method="GET" action="{{ route('dokter.search') }}" class="flex gap-2">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Cari pasien..." 
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            />
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Cari
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                @if($pasien->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Usia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status Terakhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($pasien as $index => $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $pasien->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $p->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @php
                                            $age = \Carbon\Carbon::parse($p->birthdate)->age;
                                        @endphp
                                        {{ $age ?? '-' }} tahun
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $p->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $latest = $p->healthRecords()->latest()->first();
                                        @endphp
                                        @if($latest)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($latest->status === 'Normal')
                                                    bg-green-100 text-green-800
                                                @elseif($latest->status === 'Prehipertensi')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($latest->status === 'Hipertensi Stage 1')
                                                    bg-orange-100 text-orange-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ $latest->status_emoji }} {{ $latest->status }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">Belum ada data</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('dokter.detail-pasien', $p) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t">
                        {{ $pasien->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p>Belum ada pasien yang ditugaskan ke Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
