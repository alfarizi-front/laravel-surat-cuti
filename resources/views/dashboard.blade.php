<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-tachometer-alt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-600">Sistem Manajemen Surat Cuti</p>
                </div>
            </div>
            @auth
                <div class="hidden md:flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->nama }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->jabatan }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->nama, 0,  1) }}
                    </div>
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="glass-card rounded-2xl shadow-xl mb-8 overflow-hidden card-hover">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-2 animate-fade-in">
                                Selamat datang, {{ auth()->check() ? auth()->user()->nama : 'Guest' }}! 👋
                            </h3>
                            @auth
                            <div class="flex items-center space-x-4 text-blue-100">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-briefcase"></i>
                                    <span>{{ auth()->user()->jabatan }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-building"></i>
                                    <span>{{ auth()->user()->unit_kerja }}</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    {{ auth()->user()->jenis_pegawai }}
                                </span>
                            </div>
                            @endauth
                        </div>
                        <div class="hidden md:block">
                            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-bounce-gentle">
                                <i class="fas fa-chart-line text-3xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Bar -->
                <div class="bg-white bg-opacity-50 px-6 py-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-2 text-gray-600">
                            <i class="fas fa-clock"></i>
                            <span>Terakhir login: {{ now()->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2 text-green-600">
                                <i class="fas fa-wifi"></i>
                                <span>Online</span>
                            </div>
                            <div class="flex items-center space-x-2 text-blue-600">
                                <i class="fas fa-calendar"></i>
                                <span>{{ now()->format('l, d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Surat Card -->
                    <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Surat</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $data['total_surat'] ?? 0 }}</p>
                                    <p class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Semua periode
                                    </p>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-file-alt text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1"></div>
                    </div>

                    <!-- Pending Card -->
                    <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $data['surat_pending'] ?? 0 }}</p>
                                    <p class="text-xs text-orange-600 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu disposisi
                                    </p>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-hourglass-half text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 h-1"></div>
                    </div>

                    <!-- Disetujui Card -->
                    <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Disetujui</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $data['surat_disetujui'] ?? 0 }}</p>
                                    <p class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-check mr-1"></i>
                                        Sudah selesai
                                    </p>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-check-circle text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-1"></div>
                    </div>

                    <!-- Ditolak Card -->
                    <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Ditolak</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $data['surat_ditolak'] ?? 0 }}</p>
                                    <p class="text-xs text-red-600 mt-1">
                                        <i class="fas fa-times mr-1"></i>
                                        Tidak disetujui
                                    </p>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-times-circle text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1"></div>
                    </div>
                </div>

                <!-- Quick Actions for Admin -->
                <div class="glass-card rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                            Aksi Cepat Admin
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('admin.users.create') }}"
                               class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-user-plus text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Tambah User</div>
                                    <div class="text-sm text-blue-100">Buat akun disposisi baru</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Kelola User</div>
                                    <div class="text-sm text-purple-100">Manajemen semua user</div>
                                </div>
                            </a>

                            <a href="{{ route('surat-cuti.index') }}"
                               class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-file-alt text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Semua Surat</div>
                                    <div class="text-sm text-green-100">Monitor surat cuti</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.laporan.index') }}"
                               class="flex items-center p-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-chart-bar text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Laporan Cuti</div>
                                    <div class="text-sm text-orange-100">Rekap & analisis</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.cap-stempel.index') }}"
                               class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-stamp text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">Cap KADIN</div>
                                    <div class="text-sm text-yellow-100">Kelola cap Kepala Dinas</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif(auth()->check() && auth()->user()->role === 'karyawan')
                <!-- Karyawan Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Surat</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['total_surat'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Draft</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['surat_draft'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Proses</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['surat_proses'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Disetujui</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['surat_disetujui'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions for Karyawan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('surat-cuti.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ajukan Cuti Baru
                            </a>
                            <a href="{{ route('surat-cuti.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Lihat Surat Cuti Saya
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <!-- Disposisi Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Disposisi Pending</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['pending_disposisi'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Disposisi Selesai</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $data['completed_disposisi'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions for Disposisi -->
                @if($data['pending_disposisi'] > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
                            <a href="{{ route('disposisi.pending') }}" 
                               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Proses Disposisi Pending ({{ $data['pending_disposisi'] }})
                            </a>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
                    
                    @if(auth()->user()->role === 'karyawan' && isset($data['recent_surat']))
                        @if($data['recent_surat']->count() > 0)
                            <div class="space-y-3">
                                @foreach($data['recent_surat'] as $surat)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $surat->jenisCuti->nama }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $surat->tanggal_awal->format('d/m/Y') }} - {{ $surat->tanggal_akhir->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($surat->status === 'draft') bg-gray-100 text-gray-800
                                                @elseif($surat->status === 'proses') bg-yellow-100 text-yellow-800
                                                @elseif($surat->status === 'disetujui') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($surat->status) }}
                                            </span>

                                            @if($surat->status === 'disetujui')
                                                <a href="{{ route('surat-cuti.download-pdf', $surat) }}"
                                                   class="inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition-colors duration-200"
                                                   title="Download PDF">
                                                    <i class="fas fa-download mr-1"></i>
                                                    PDF
                                                </a>
                                            @endif

                                            <a href="{{ route('surat-cuti.show', $surat) }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada surat cuti yang diajukan.</p>
                        @endif
                    @elseif(isset($data['recent_disposisi']))
                        @if($data['recent_disposisi']->count() > 0)
                            <div class="space-y-3">
                                @foreach($data['recent_disposisi'] as $disposisi)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <!-- Pemohon Info Header -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $disposisi->suratCuti->pengaju->nama }}
                                                        </p>
                                                        @if($disposisi->suratCuti->pengaju->nip)
                                                            <p class="text-xs text-blue-600 font-mono">
                                                                NIP: {{ $disposisi->suratCuti->pengaju->nip }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $disposisi->tipe_disposisi === 'ttd' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $disposisi->tipe_disposisi === 'ttd' ? '🖋️ TTD' : '✍️ Paraf' }}
                                                </span>
                                            </div>

                                            <!-- Detail Info -->
                                            <div class="space-y-1">
                                                <p class="text-xs text-gray-600">
                                                    <i class="fas fa-briefcase mr-1"></i>
                                                    <span class="font-medium">{{ $disposisi->suratCuti->pengaju->jabatan }}</span> -
                                                    {{ $disposisi->suratCuti->pengaju->unit_kerja }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    <span class="font-medium">{{ $disposisi->suratCuti->jenisCuti->nama }}</span> |
                                                    {{ $disposisi->suratCuti->tanggal_awal->format('d/m/Y') }} - {{ $disposisi->suratCuti->tanggal_akhir->format('d/m/Y') }}
                                                    <span class="bg-gray-100 px-1 rounded text-xs ml-1">{{ $disposisi->suratCuti->jumlah_hari }} hari</span>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-comment mr-1"></i>
                                                    {{ Str::limit($disposisi->suratCuti->alasan, 50) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($disposisi->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $disposisi->status === 'pending' ? 'Pending' : 'Selesai' }}
                                            </span>
                                            @if($disposisi->status === 'pending')
                                                <a href="{{ route('disposisi.show', $disposisi) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                                    Proses
                                                </a>
                                            @else
                                                <a href="{{ route('surat-cuti.show', $disposisi->suratCuti) }}" 
                                                   class="text-gray-600 hover:text-gray-800 text-sm">
                                                    Detail
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada disposisi.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
