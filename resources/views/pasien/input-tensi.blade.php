<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Data Tensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900">Catat Data Tensi Anda</h3>
                    <p class="text-gray-600 mt-2">Masukkan pembacaan tekanan darah terbaru Anda</p>
                </div>

                <form method="POST" action="{{ route('pasien.store-tensi') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Sistolik -->
                    <div>
                        <x-input-label for="sistolik" :value="__('Sistolik (mmHg)')" />
                        <x-text-input 
                            id="sistolik" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="sistolik" 
                            :value="old('sistolik')" 
                            required 
                            min="50"
                            max="250"
                            placeholder="150"
                        />
                        <p class="text-xs text-gray-500 mt-1">Range: 50 - 250 mmHg</p>
                        <x-input-error :messages="$errors->get('sistolik')" class="mt-2" />
                    </div>

                    <!-- Diastolik -->
                    <div>
                        <x-input-label for="diastolik" :value="__('Diastolik (mmHg)')" />
                        <x-text-input 
                            id="diastolik" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="diastolik" 
                            :value="old('diastolik')" 
                            required 
                            min="30"
                            max="150"
                            placeholder="90"
                        />
                        <p class="text-xs text-gray-500 mt-1">Range: 30 - 150 mmHg</p>
                        <x-input-error :messages="$errors->get('diastolik')" class="mt-2" />
                    </div>

                    <!-- Denyut Nadi -->
                    <div>
                        <x-input-label for="denyut_nadi" :value="__('Denyut Nadi (bpm)')" />
                        <x-text-input 
                            id="denyut_nadi" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="denyut_nadi" 
                            :value="old('denyut_nadi')" 
                            required 
                            min="30"
                            max="200"
                            placeholder="72"
                        />
                        <p class="text-xs text-gray-500 mt-1">Range: 30 - 200 bpm</p>
                        <x-input-error :messages="$errors->get('denyut_nadi')" class="mt-2" />
                    </div>

                    <!-- Catatan -->
                    <div>
                        <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                        <textarea 
                            id="catatan" 
                            name="catatan" 
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="4"
                            placeholder="Kondisi, aktivitas sebelum pengukuran, dll..."
                        >{{ old('catatan') }}</textarea>
                        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                    </div>

                    <!-- Information Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Kategori Tekanan Darah</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>🟢 <strong>Normal:</strong> Sistolik < 120 dan Diastolik < 80</li>
                            <li>🟡 <strong>Prehipertensi:</strong> Sistolik 120-139 atau Diastolik 80-89</li>
                            <li>🟠 <strong>Hipertensi Stage 1:</strong> Sistolik 140-159 atau Diastolik 90-99</li>
                            <li>🔴 <strong>Hipertensi Stage 2:</strong> Sistolik ≥ 160 atau Diastolik ≥ 100</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 justify-end">
                        <a href="{{ route('pasien.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
