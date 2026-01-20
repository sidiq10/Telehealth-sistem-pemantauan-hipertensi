<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-1">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @php
                        $dashboardRoute = auth()->user()->role === 'pasien' ? route('pasien.dashboard') : route('dokter.dashboard');
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        <span class="text-white font-bold text-lg hidden sm:inline">TeleHealth</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-8 sm:flex items-center">
                    <a href="{{ $dashboardRoute }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium" :class="{'bg-blue-400': request()->routeIs('pasien.dashboard', 'dokter.dashboard')}">
                        {{ __('Dashboard') }}
                    </a>
                @if (auth()->user()->role === 'pasien')
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('pasien.input-tensi') }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium">
                        + Tensi
                    </a>
                    <a href="{{ route('pasien.riwayat') }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium">
                        📊 Riwayat
                    </a>
                    <a href="{{ route('pasien.grafik') }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium">
                        📈 Grafik
                    </a>
                    <a href="{{ route('pasien.feedback') }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium">
                        💬 Dokter
                    </a>
                    <a href="{{ route('pasien.chatbot') }}" class="text-white px-3 py-2 text-base rounded-md hover:bg-blue-400 transition font-medium">
                        🤖 Bot
                    </a>
                </div>
                @endif  
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center gap-3">
                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-white bg-blue-500 text-white text-sm leading-4 font-medium rounded-lg hover:bg-blue-400 focus:outline-none transition ease-in-out duration-150">
                            <div class="truncate">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:bg-blue-500 focus:outline-none focus:bg-blue-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-500">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ $dashboardRoute }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                {{ __('Dashboard') }}
            </a>
        </div>

        <!-- Responsive Action Buttons for Patient -->
        @if (auth()->user()->role === 'pasien')
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('pasien.input-tensi') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                + Tensi
            </a>
            <a href="{{ route('pasien.riwayat') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                📊 Riwayat
            </a>
            <a href="{{ route('pasien.grafik') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                📈 Grafik
            </a>
            <a href="{{ route('pasien.feedback') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                💬 Dokter
            </a>
            <a href="{{ route('pasien.chatbot') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                🤖 Chatbot
            </a>
        </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-3 pb-1 border-t border-blue-400">
            <div class="px-3">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-blue-100">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-400">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
