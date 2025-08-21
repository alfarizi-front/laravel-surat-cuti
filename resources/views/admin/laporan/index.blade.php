<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-bar text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        Laporan Surat Cuti
                    </h2>
                    <p class="text-sm text-gray-600">Rekap dan analisis surat cuti yang disetujui</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="printLaporan()" 
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
                <button onclick="exportLaporan()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Surat</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($statistik['total_surat']) }}</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-file-alt text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1 mt-4"></div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Hari Cuti</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($statistik['total_hari']) }}</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calendar-days text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-1 mt-4"></div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Rata-rata Hari</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $statistik['rata_rata_hari'] }}</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-1 mt-4"></div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Periode Filter</p>
                                <p class="text-lg font-bold text-gray-900">
                                    @if(request('bulan') && request('tahun'))
                                        {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }} {{ request('tahun') }}
                                    @elseif(request('tanggal_mulai') || request('tanggal_selesai'))
                                        Custom Range
                                    @else
                                        Semua Data
                                    @endif
                                </p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-filter text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-1 mt-4"></div>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-500"></i>
                        Filter Laporan
                    </h3>
                    
                    <form method="GET" action="{{ route('admin.laporan.index') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Filter Tanggal -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-700">Filter Tanggal</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div class="text-center text-gray-500 text-sm">atau</div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Bulan</label>
                                        <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Bulan</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
                                        <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Tahun</option>
                                            @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Pegawai -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-700">Filter Pegawai</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pegawai</label>
                                        <input type="text" name="nama_pegawai" value="{{ request('nama_pegawai') }}" 
                                               placeholder="Cari nama pegawai..."
                                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Unit Kerja</label>
                                        <select name="unit_kerja" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Unit Kerja</option>
                                            @foreach($unitKerja as $unit)
                                                <option value="{{ $unit }}" {{ request('unit_kerja') == $unit ? 'selected' : '' }}>
                                                    {{ $unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Pegawai</label>
                                        <select name="jenis_pegawai" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Jenis</option>
                                            @foreach($jenisPegawai as $jenis)
                                                <option value="{{ $jenis }}" {{ request('jenis_pegawai') == $jenis ? 'selected' : '' }}>
                                                    {{ $jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Cuti -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-700">Filter Cuti</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Cuti</label>
                                        <select name="jenis_cuti_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Jenis Cuti</option>
                                            @foreach($jenisCuti as $jenis)
                                                <option value="{{ $jenis->id }}" {{ request('jenis_cuti_id') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Urutkan Berdasarkan</label>
                                        <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="tanggal_awal" {{ request('sort_by') == 'tanggal_awal' ? 'selected' : '' }}>Tanggal Mulai Cuti</option>
                                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Pengajuan</option>
                                            <option value="jumlah_hari" {{ request('sort_by') == 'jumlah_hari' ? 'selected' : '' }}>Jumlah Hari</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Urutan</label>
                                        <select name="sort_order" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-search mr-2"></i>
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('admin.laporan.index') }}"
                                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-refresh mr-2"></i>
                                    Reset Filter
                                </a>
                            </div>
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $suratCuti->count() }} dari {{ $suratCuti->total() }} data
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-table mr-2 text-green-500"></i>
                            Data Surat Cuti Disetujui
                        </h3>
                        <div class="text-sm text-gray-600">
                            Total: {{ $suratCuti->total() }} surat
                        </div>
                    </div>

                    @if($suratCuti->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratCuti as $index => $surat)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ($suratCuti->currentPage() - 1) * $suratCuti->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                                        {{ substr($surat->pengaju->nama, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $surat->pengaju->nama }}</div>
                                                        <div class="text-sm text-gray-500">{{ $surat->pengaju->nip ?: 'Tanpa NIP' }}</div>
                                                        <div class="text-xs text-gray-400">{{ $surat->pengaju->jenis_pegawai }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $surat->pengaju->unit_kerja }}</div>
                                                <div class="text-sm text-gray-500">{{ $surat->pengaju->jabatan }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $surat->jenisCuti->nama ?? 'Cuti' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>{{ $surat->tanggal_awal->format('d M Y') }}</div>
                                                <div class="text-gray-500">s/d {{ $surat->tanggal_akhir->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ $surat->jumlah_hari }} hari
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Disetujui
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('surat-cuti.show', $surat) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Detail
                                                </a>
                                                <a href="{{ route('surat-cuti.pdf', $surat) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-file-pdf mr-1"></i>
                                                    PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $suratCuti->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data</h3>
                            <p class="text-gray-500">Tidak ada surat cuti yang sesuai dengan filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistik Charts -->
            @if($statistik['per_jenis_cuti']->count() > 0 || $statistik['per_unit_kerja']->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Chart Per Jenis Cuti -->
                    @if($statistik['per_jenis_cuti']->count() > 0)
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-chart-pie mr-2 text-purple-500"></i>
                                    Statistik Per Jenis Cuti
                                </h3>
                                <div class="space-y-4">
                                    @foreach($statistik['per_jenis_cuti'] as $stat)
                                        @php
                                            $percentage = $statistik['total_surat'] > 0 ? ($stat->total / $statistik['total_surat']) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $stat->jenisCuti->nama ?? 'Cuti' }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $stat->total }} surat ({{ number_format($percentage, 1) }}%)
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Total {{ $stat->total_hari }} hari
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Chart Per Unit Kerja -->
                    @if($statistik['per_unit_kerja']->count() > 0)
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-chart-bar mr-2 text-orange-500"></i>
                                    Statistik Per Unit Kerja
                                </h3>
                                <div class="space-y-4">
                                    @foreach($statistik['per_unit_kerja']->take(10) as $stat)
                                        @php
                                            $percentage = $statistik['total_surat'] > 0 ? ($stat->total / $statistik['total_surat']) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $stat->unit_kerja }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $stat->total }} surat
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Total {{ $stat->total_hari }} hari
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        function exportLaporan() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '{{ route("admin.laporan.export") }}?' + params.toString();
        }

        function printLaporan() {
            const params = new URLSearchParams(window.location.search);
            window.open('{{ route("admin.laporan.print") }}?' + params.toString(), '_blank');
        }
    </script>
</x-app-layout>
