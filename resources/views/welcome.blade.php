<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TeleHealth - Sistem Pemantauan Kesehatan Hipertensi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-slate-50 to-white text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-lg">💊</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-gray-900 block">TeleHealth</span>
                        <span class="text-xs text-gray-500">Pantau Kesehatan Anda</span>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <div class="flex items-center space-x-4 border-r border-gray-200 pr-6">
                            <span class="text-sm text-gray-600">👤 {{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ auth()->user()->role === 'pasien' ? route('pasien.dashboard') : route('dokter.dashboard') }}" 
                           class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium text-sm">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu -->
                <div class="md:hidden flex items-center space-x-3">
                    @auth
                        <a href="{{ auth()->user()->role === 'pasien' ? route('pasien.dashboard') : route('dokter.dashboard') }}" 
                           class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-gray-700 text-xs font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                            Pantau Tekanan Darah Anda dengan <span class="text-blue-600">Lebih Baik</span>
                        </h1>
                        <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                            TeleHealth adalah sistem modern untuk memantau kesehatan hipertensi Anda secara real-time. 
                            Catat data tensi, lihat tren kesehatan, dan berkomunikasi langsung dengan dokter Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        @auth
                            <a href="{{ auth()->user()->role === 'pasien' ? route('pasien.dashboard') : route('dokter.dashboard') }}" 
                               class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition text-center">
                                📊 Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition text-center">
                                🚀 Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" 
                               class="px-8 py-4 bg-gray-100 text-gray-900 rounded-lg font-semibold hover:bg-gray-200 transition text-center border-2 border-gray-200">
                                🔐 Login
                            </a>
                        @endauth
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-gray-200">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">50+</div>
                            <div class="text-xs text-gray-600">Data Records</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">2</div>
                            <div class="text-xs text-gray-600">Dokter Aktif</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">5</div>
                            <div class="text-xs text-gray-600">Pasien Terdaftar</div>
                        </div>
                    </div>
                </div>

                <!-- Right Illustration Card -->
                <div class="relative hidden md:block">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-200 to-indigo-200 rounded-3xl blur-2xl opacity-30"></div>
                    <div class="relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-200 shadow-xl">
                        <!-- Health Card Display -->
                        <div class="space-y-6">
                            <!-- Reading -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Pembacaan Terakhir</p>
                                        <p class="text-xs text-gray-400">13 Nov 2025, 14:30</p>
                                    </div>
                                    <span class="text-3xl">🟢</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                                        <div class="text-2xl font-bold text-blue-600">120</div>
                                        <div class="text-xs text-gray-600">Sistolik</div>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-3 text-center">
                                        <div class="text-2xl font-bold text-green-600">80</div>
                                        <div class="text-xs text-gray-600">Diastolik</div>
                                    </div>
                                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                                        <div class="text-2xl font-bold text-purple-600">72</div>
                                        <div class="text-xs text-gray-600">Nadi</div>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm font-semibold text-green-900">✓ Status Normal</p>
                                    <p class="text-xs text-green-700">Tekanan darah ideal</p>
                                </div>
                            </div>

                            <!-- Chart Preview -->
                            <div class="bg-white rounded-2xl p-4 shadow-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Tren 7 Hari</p>
                                <div class="flex items-end justify-around h-20 gap-1">
                                    <div class="w-6 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t opacity-60" style="height: 40%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t opacity-70" style="height: 55%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t" style="height: 70%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t opacity-65" style="height: 50%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t opacity-75" style="height: 60%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-green-400 to-green-300 rounded-t" style="height: 65%;"></div>
                                    <div class="w-6 bg-gradient-to-t from-green-400 to-green-300 rounded-t opacity-80" style="height: 45%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Platform TeleHealth menyediakan semua yang Anda butuhkan untuk mengelola kesehatan hipertensi dengan lebih baik
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => '📱', 'title' => 'Input Data Mudah', 'desc' => 'Catat tekanan darah kapan saja dengan interface yang user-friendly'],
                        ['icon' => '📊', 'title' => 'Grafik Interaktif', 'desc' => 'Visualisasi tren kesehatan dengan chart.js untuk 7, 14, atau 30 hari'],
                        ['icon' => '👨‍⚕️', 'title' => 'Monitoring Dokter', 'desc' => 'Dokter dapat memantau data dan memberikan feedback langsung'],
                        ['icon' => '💬', 'title' => 'Sistem Chat', 'desc' => 'Komunikasi dua arah dengan dokter untuk konsultasi personal'],
                        ['icon' => '🔐', 'title' => 'Data Aman', 'desc' => 'Semua data terenkripsi dan terlindungi dengan standar keamanan tinggi'],
                        ['icon' => '📈', 'title' => 'Statistik Real-time', 'desc' => 'Akses min, max, rata-rata, dan deviasi standar data Anda'],
                    ];
                @endphp
                
                @foreach($features as $feature)
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition duration-300 border border-gray-100">
                    <div class="text-5xl mb-4">{{ $feature['icon'] }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Health Status Categories -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Kategori Status Kesehatan</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pahami status tekanan darah Anda dengan kategori yang sesuai standar medis
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <!-- Normal -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🟢</div>
                    <h3 class="font-bold text-green-900 mb-1">Normal</h3>
                    <p class="text-sm text-green-700 font-semibold mb-2">&lt;120/&lt;80</p>
                    <p class="text-xs text-green-600">Tekanan darah ideal</p>
                </div>

                <!-- Prehipertensi -->
                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl p-6 border-2 border-yellow-200 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🟡</div>
                    <h3 class="font-bold text-yellow-900 mb-1">Prehipertensi</h3>
                    <p class="text-sm text-yellow-700 font-semibold mb-2">120-139/80-89</p>
                    <p class="text-xs text-yellow-600">Monitor lebih rutin</p>
                </div>

                <!-- Stage 1 -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border-2 border-orange-200 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🟠</div>
                    <h3 class="font-bold text-orange-900 mb-1">Hipertensi I</h3>
                    <p class="text-sm text-orange-700 font-semibold mb-2">140-159/90-99</p>
                    <p class="text-xs text-orange-600">Konsultasi dokter</p>
                </div>

                <!-- Stage 2 -->
                <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl p-6 border-2 border-red-200 shadow-md hover:shadow-lg transition">
                    <div class="text-4xl mb-3">🔴</div>
                    <h3 class="font-bold text-red-900 mb-1">Hipertensi II</h3>
                    <p class="text-sm text-red-700 font-semibold mb-2">≥160/≥100</p>
                    <p class="text-xs text-red-600">Hubungi dokter segera</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Mulai Pantau Kesehatan Anda Sekarang</h2>
            <p class="text-lg text-blue-100 mb-10 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan pengguna yang telah merasakan manfaat TeleHealth untuk kesehatan optimal mereka
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ auth()->user()->role === 'pasien' ? route('pasien.dashboard') : route('dokter.dashboard') }}" 
                           class="inline-flex items-center justify-center px-8 py-3 rounded-lg font-semibold text-blue-600 bg-white hover:bg-gray-100 transition">
                            📊 Kembali ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center px-8 py-3 rounded-lg font-semibold text-white bg-blue-500 hover:bg-blue-600 transition">
                            🚀 Daftar Sekarang
                        </a>

                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center justify-center px-8 py-3 rounded-lg font-semibold text-white border-2 border-white hover:bg-blue-700 transition">
                            🔐 Sudah Punya Akun
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-800 to-gray-900 text-gray-300 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-md">
                            <span class="text-white font-bold text-lg">💊</span>
                        </div>
                        <span class="text-white font-bold text-lg">TeleHealth</span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Sistem pemantauan kesehatan hipertensi berbasis web dengan monitoring real-time dan konsultasi dokter
                    </p>
                </div>
                
                <!-- Features Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Fitur</h4>
                    <ul class="text-sm space-y-3">
                        <li><a href="#" class="hover:text-white transition">📱 Input Data</a></li>
                        <li><a href="#" class="hover:text-white transition">📊 Grafik & Statistik</a></li>
                        <li><a href="#" class="hover:text-white transition">👨‍⚕️ Monitoring Dokter</a></li>
                        <li><a href="#" class="hover:text-white transition">💬 Komunikasi</a></li>
                    </ul>
                </div>

                <!-- About Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Perusahaan</h4>
                    <ul class="text-sm space-y-3">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Dukungan</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Legal & Contact -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Legal</h4>
                    <ul class="text-sm space-y-3">
                        <li><a href="#" class="hover:text-white transition">Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Keamanan</a></li>
                        <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-700"></div>

            <!-- Bottom Footer -->
            <div class="pt-8 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-sm text-gray-400">
                        &copy; 2025 TeleHealth_M.Yasin Assidiq. Semua hak dilindungi. Dibuat dengan ❤️ untuk kesehatan Anda.
                    </p>
                </div>
                <div class="flex space-x-6 justify-end">
                    <a href="#" class="text-gray-400 hover:text-white transition text-sm">Facebook</a>
                    <a href="#" class="text-gray-400 hover:text-white transition text-sm">Twitter</a>
                    <a href="#" class="text-gray-400 hover:text-white transition text-sm">Instagram</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
