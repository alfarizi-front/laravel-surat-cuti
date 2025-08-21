<nav x-data="{ open: false }" class="glass-card shadow-lg border-b border-white border-opacity-20 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <i class="fas fa-file-medical text-white text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Sistem Surat Cuti</span>
                            @auth
                                <span class="text-xs text-gray-500">{{ auth()->user()->unit_kerja }}</span>
                            @endauth
                        </div>
                        @auth
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-md animate-pulse-slow">
                                {{ auth()->user()->role }}
                            </span>
                        @endauth
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-item">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->check() && auth()->user()->role === 'karyawan')
                        <x-nav-link :href="route('surat-cuti.index')" :active="request()->routeIs('surat-cuti.*')" class="nav-item">
                            <i class="fas fa-file-alt mr-2"></i>
                            {{ __('Surat Cuti') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['kasubag', 'kepala', 'sekdin', 'kadin']))
                        <x-nav-link :href="route('disposisi.pending')" :active="request()->routeIs('disposisi.pending')" class="nav-item">
                            <i class="fas fa-tasks mr-2"></i>
                            {{ __('Disposisi Pending') }}
                            @php
                                $pendingCount = \App\Models\DisposisiCuti::where('user_id', auth()->id())
                                                                        ->where('status', 'pending')
                                                                        ->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-500 text-white animate-bounce-gentle">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </x-nav-link>
                    @endif

                    @if(auth()->check())
                        <x-nav-link :href="route('disposisi.history')" :active="request()->routeIs('disposisi.history')" class="nav-item">
                            <i class="fas fa-history mr-2"></i>
                            {{ __('Riwayat Disposisi') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.surat-cuti.admin-dashboard')" :active="request()->routeIs('admin.surat-cuti.admin-dashboard')" class="nav-item">
                            <i class="fas fa-rocket mr-2"></i>
                            {{ __('🚀 Bulk Approval (Debug)') }}
                        </x-nav-link>
                        <x-nav-link :href="route('surat-cuti.index')" :active="request()->routeIs('surat-cuti.*') && !request()->routeIs('admin.*')" class="nav-item">
                            <i class="fas fa-file-alt mr-2"></i>
                            {{ __('Semua Surat Cuti') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="nav-item">
                            <i class="fas fa-users mr-2"></i>
                            {{ __('Kelola User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')" class="nav-item">
                            <i class="fas fa-chart-bar mr-2"></i>
                            {{ __('Laporan Cuti') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.cap-stempel.index')" :active="request()->routeIs('admin.cap-stempel.*')" class="nav-item">
                            <i class="fas fa-stamp mr-2"></i>
                            {{ __('Cap & Tanda Tangan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::check() ? Auth::user()->nama : 'Guest' }}</div>

                            <div class="ml-1">
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

                        @if(auth()->check() && in_array(auth()->user()->role, ['kadin', 'sekdin', 'kepala', 'kasubag']))
                            <x-dropdown-link :href="route('signature.upload')">
                                @if(auth()->user()->role === 'kadin' || str_contains(strtolower(auth()->user()->jabatan), 'kepala dinas'))
                                    {{ __('Tanda Tangan & Cap') }}
                                @else
                                    {{ __('Tanda Tangan') }}
                                @endif
                            </x-dropdown-link>
                        @endif

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
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->check() && auth()->user()->role === 'karyawan')
                <x-responsive-nav-link :href="route('surat-cuti.index')" :active="request()->routeIs('surat-cuti.*')">
                    {{ __('Surat Cuti') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->check() && in_array(auth()->user()->role, ['kasubag', 'kepala', 'sekdin', 'kadin']))
                <x-responsive-nav-link :href="route('disposisi.pending')" :active="request()->routeIs('disposisi.pending')">
                    {{ __('Disposisi Pending') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->check())
                <x-responsive-nav-link :href="route('disposisi.history')" :active="request()->routeIs('disposisi.history')">
                    {{ __('Riwayat Disposisi') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->check() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.surat-cuti.admin-dashboard')" :active="request()->routeIs('admin.surat-cuti.admin-dashboard')">
                    {{ __('🚀 Bulk Approval (Debug)') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('surat-cuti.index')" :active="request()->routeIs('surat-cuti.*') && !request()->routeIs('admin.*')">
                    {{ __('Semua Surat Cuti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Kelola User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                    {{ __('Laporan Cuti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.cap-stempel.index')" :active="request()->routeIs('admin.cap-stempel.*')">
                    {{ __('Cap & Tanda Tangan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::check() ? Auth::user()->nama : 'Guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::check() ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
