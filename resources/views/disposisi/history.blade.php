<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Disposisi') }}
            </h2>
            <div class="text-sm text-gray-500">
                <a href="{{ route('disposisi.pending') }}" class="text-blue-600 hover:text-blue-800">Disposisi Pending</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Disposisi yang Saya Proses -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Disposisi yang Saya Proses</h3>

                    @if($myDisposisi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaju</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Disposisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($myDisposisi as $d)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $d->suratCuti->pengaju->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $d->suratCuti->pengaju->jabatan }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $d->suratCuti->jenisCuti->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $d->suratCuti->tanggal_awal->format('d/m/Y') }} - {{ $d->suratCuti->tanggal_akhir->format('d/m/Y') }}
                                                <div class="text-xs text-gray-500">({{ $d->suratCuti->jumlah_hari }} hari)</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($d->status === 'sudah') bg-green-100 text-green-800
                                                    @elseif($d->status === 'ditolak') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ strtoupper($d->status) }}
                                                </span>
                                                @if($d->catatan)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $d->catatan }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $d->tanggal ? $d->tanggal->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">{{ $myDisposisi->onEachSide(0)->links() }}</div>
                    @else
                        <div class="text-center py-8">
                            <h4 class="text-sm font-medium text-gray-900">Belum ada riwayat disposisi</h4>
                            <p class="text-sm text-gray-500">Riwayat akan muncul setelah Anda memproses disposisi.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pengajuan Saya -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Pengajuan Saya</h3>

                    @if($mySubmissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Disposisi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mySubmissions as $s)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $s->jenisCuti->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $s->tanggal_awal->format('d/m/Y') }} - {{ $s->tanggal_akhir->format('d/m/Y') }}
                                                <div class="text-xs text-gray-500">({{ $s->jumlah_hari }} hari)</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($s->status === 'disetujui') bg-green-100 text-green-800
                                                    @elseif($s->status === 'proses') bg-yellow-100 text-yellow-800
                                                    @elseif($s->status === 'ditolak') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($s->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($s->disposisiCuti->count() > 0)
                                                    <div class="text-xs text-gray-700">
                                                        @foreach($s->disposisiCuti as $d)
                                                            <div class="mb-1">
                                                                <span class="font-medium">{{ $d->jabatan }}</span>
                                                                — {{ $d->user->nama ?? '-' }}
                                                                <span class="ml-2">
                                                                    @if($d->status === 'sudah')
                                                                        <span class="text-green-600">DISETUJUI</span>
                                                                    @elseif($d->status === 'ditolak')
                                                                        <span class="text-red-600">DITOLAK</span>
                                                                    @else
                                                                        <span class="text-yellow-600">PENDING</span>
                                                                    @endif
                                                                </span>
                                                                <span class="ml-2 text-gray-500">{{ $d->tanggal ? $d->tanggal->format('d/m/Y') : '-' }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400">(belum ada disposisi)</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">{{ $mySubmissions->onEachSide(0)->links() }}</div>
                    @else
                        <div class="text-center py-8">
                            <h4 class="text-sm font-medium text-gray-900">Belum ada pengajuan surat cuti</h4>
                            <p class="text-sm text-gray-500">Ajukan surat cuti baru untuk memulai proses disposisi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
