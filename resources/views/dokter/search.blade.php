<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cari Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('dokter.search') }}" class="flex gap-4 mb-4">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $query }}"
                            placeholder="Cari nama atau email pasien..." 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        />
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Cari
                        </button>
                        <a href="{{ route('dokter.dashboard') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Reset
                        </a>
                    </form>
                </div>

                <!-- Results -->
                @if($pasien->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Usia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status Terakhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($pasien as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $p->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($p->birthdate)->age ?? '-' }} tahun
                                    </td>
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
                    <div class="p-6 border-t">
                        {{ $pasien->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        @if($query)
                            Tidak ada hasil pencarian untuk "{{ $query }}"
                        @else
                            Masukkan kata kunci untuk mencari pasien
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
