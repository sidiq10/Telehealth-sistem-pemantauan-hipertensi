<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Data Tensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Riwayat Data Tensi</h3>
                        <p class="text-gray-600 mt-1">Total: {{ $records->total() }} data</p>
                    </div>
                    <a href="{{ route('pasien.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        ← Kembali ke Dashboard
                    </a>
                </div>

                <!-- Data Table -->
                @if($records->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Sistolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Diastolik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">DN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($records as $index => $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $records->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">{{ $record->sistolik }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">{{ $record->diastolik }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->denyut_nadi }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($record->status === 'Normal')
                                                bg-green-100 text-green-800
                                            @elseif($record->status === 'Prehipertensi')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($record->status === 'Hipertensi Stage 1')
                                                bg-orange-100 text-orange-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ $record->status_emoji }} {{ $record->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($record->catatan, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t">
                        {{ $records->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p class="mb-4">Belum ada data tensi yang dicatat.</p>
                        <a href="{{ route('pasien.input-tensi') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Mulai input data →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
