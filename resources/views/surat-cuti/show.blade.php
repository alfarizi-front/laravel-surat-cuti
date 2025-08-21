<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Surat Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header Info -->
                    <div class="mb-6 border-b pb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold">Surat Cuti #{{ $suratCuti->id }}</h3>
                                <p class="text-gray-600">{{ $suratCuti->pengaju->nama }} - {{ $suratCuti->pengaju->jabatan }}</p>
                                <p class="text-sm text-gray-500">{{ $suratCuti->pengaju->unit_kerja }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($suratCuti->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($suratCuti->status === 'proses') bg-yellow-100 text-yellow-800
                                    @elseif($suratCuti->status === 'disetujui') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($suratCuti->status) }}
                                </span>
                                @if($suratCuti->tanggal_ajuan)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Diajukan: {{ $suratCuti->tanggal_ajuan->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Limit Cuti -->
                    @if(session('cuti_info'))
                        @php $cutiInfo = session('cuti_info'); @endphp
                        <div class="mb-6 p-4 rounded-lg border
                            @if($cutiInfo['is_exceeding'])
                                bg-red-50 border-red-200
                            @elseif($cutiInfo['total_if_approved'] > $cutiInfo['jatah_cuti'] * 0.8)
                                bg-yellow-50 border-yellow-200
                            @else
                                bg-green-50 border-green-200
                            @endif">
                            <div class="flex items-center mb-2">
                                @if($cutiInfo['is_exceeding'])
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <h4 class="font-semibold text-red-800">Peringatan: Melebihi Batas Maksimal</h4>
                                @elseif($cutiInfo['total_if_approved'] > $cutiInfo['jatah_cuti'] * 0.8)
                                    <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                                    <h4 class="font-semibold text-yellow-800">Perhatian: Mendekati Batas Maksimal</h4>
                                @else
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <h4 class="font-semibold text-green-800">Pengajuan Normal</h4>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Pengajuan ini:</span>
                                    <div class="font-semibold">{{ $cutiInfo['jumlah_hari'] }} hari</div>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total jika disetujui:</span>
                                    <div class="font-semibold">{{ $cutiInfo['total_if_approved'] }} hari</div>
                                </div>
                                <div>
                                    <span class="text-gray-600">Batas maksimal:</span>
                                    <div class="font-semibold">{{ $cutiInfo['jatah_cuti'] }} hari</div>
                                </div>
                                <div>
                                    <span class="text-gray-600">Sisa cuti saat ini:</span>
                                    <div class="font-semibold">{{ $cutiInfo['sisa_cuti'] }} hari</div>
                                </div>
                            </div>
                            @if($cutiInfo['is_exceeding'])
                                <p class="mt-2 text-sm text-red-700">
                                    Pengajuan ini melebihi batas maksimal cuti tahunan. Pengajuan tetap bisa disubmit namun akan memerlukan persetujuan khusus dari admin.
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Detail Cuti -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Cuti</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jenis Cuti</dt>
                                    <dd class="text-sm text-gray-900">{{ $suratCuti->jenisCuti->nama }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Cuti</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $suratCuti->tanggal_awal->format('d/m/Y') }} - {{ $suratCuti->tanggal_akhir->format('d/m/Y') }}
                                        ({{ $suratCuti->jumlah_hari }} hari)
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alasan</dt>
                                    <dd class="text-sm text-gray-900">{{ $suratCuti->alasan }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($suratCuti->pengaju->jenis_pegawai === 'ASN')
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Informasi Tambahan</h4>
                                <dl class="space-y-2">
                                    @if($suratCuti->alamat_selama_cuti)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Alamat Selama Cuti</dt>
                                            <dd class="text-sm text-gray-900">{{ $suratCuti->alamat_selama_cuti }}</dd>
                                        </div>
                                    @endif
                                    @if($suratCuti->kontak_darurat)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Kontak Darurat</dt>
                                            <dd class="text-sm text-gray-900">{{ $suratCuti->kontak_darurat }}</dd>
                                        </div>
                                    @endif
                                    @if($suratCuti->lampiran)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Lampiran</dt>
                                            <dd class="text-sm text-gray-900">
                                                <a href="{{ Storage::url($suratCuti->lampiran) }}" target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    Lihat Lampiran
                                                </a>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Disposisi -->
                    @if($suratCuti->disposisiCuti->count() > 0)
                        <div class="border-t pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-900">Riwayat Disposisi</h4>
                                @if($suratCuti->pengaju->unit_kerja === 'Sekretariat')
                                    <div class="flex items-center text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Conditional Logic: Umpeg/Perencanaan Keu
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @foreach($suratCuti->disposisiCuti as $disposisi)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                @if($disposisi->status === 'sudah') bg-green-100 text-green-600
                                                @else bg-yellow-100 text-yellow-600 @endif">
                                                @if($disposisi->status === 'sudah')
                                                    @if(str_contains($disposisi->catatan ?? '', 'Otomatis disetujui'))
                                                        <i class="fas fa-robot text-xs"></i>
                                                    @else
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                @else
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $disposisi->jabatan }} - {{ $disposisi->user->nama }}
                                                    @if(str_contains($disposisi->catatan ?? '', 'Otomatis disetujui'))
                                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-robot mr-1"></i>Auto
                                                        </span>
                                                    @endif
                                                </p>
                                                <span class="text-xs text-gray-500">
                                                    {{ $disposisi->tipe_disposisi === 'ttd' ? 'Tanda Tangan' : 'Paraf' }}
                                                </span>
                                            </div>
                                            @if($disposisi->status === 'sudah')
                                                <p class="text-xs text-gray-500">
                                                    Diproses: {{ $disposisi->tanggal->format('d/m/Y H:i') }}
                                                </p>
                                                @if($disposisi->catatan)
                                                    <div class="text-sm mt-1
                                                        @if(str_contains($disposisi->catatan, 'Otomatis disetujui'))
                                                            text-blue-700 bg-blue-50 p-2 rounded border-l-4 border-blue-400
                                                        @else
                                                            text-gray-700
                                                        @endif">
                                                        @if(str_contains($disposisi->catatan, 'Otomatis disetujui'))
                                                            <i class="fas fa-robot mr-1"></i>
                                                        @endif
                                                        {{ $disposisi->catatan }}
                                                    </div>
                                                @endif
                                            @else
                                                <p class="text-xs text-yellow-600">Menunggu disposisi</p>
                                                @if($suratCuti->pengaju->unit_kerja === 'Sekretariat' &&
                                                    in_array($disposisi->jabatan, ['Kasubag Umpeg', 'Kasubag Perencanaan Keu']))
                                                    <p class="text-xs text-blue-600 mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Akan otomatis disetujui jika yang lain approve
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-6 pt-6 border-t">
                        <a href="{{ route('surat-cuti.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Kembali
                        </a>

                        @if($suratCuti->pengaju_id === auth()->id() && $suratCuti->status === 'draft')
                            <form method="POST" action="{{ route('surat-cuti.submit', $suratCuti) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Yakin ingin submit surat cuti ini? Setelah disubmit, surat tidak dapat diubah.')">
                                    Submit untuk Disposisi
                                </button>
                            </form>
                        @endif

                        @php
                            // Check if PDF can be generated (flexible logic)
                            $disposisiList = $suratCuti->disposisiCuti;
                            $signatures = $disposisiList->where('tipe_disposisi', 'ttd');
                            $approvedSignatures = $signatures->where('status', 'sudah');
                            $allSignaturesComplete = $signatures->count() === $approvedSignatures->count();

                            $parafs = $disposisiList->where('tipe_disposisi', 'paraf');
                            $approvedParafs = $parafs->where('status', 'sudah');
                            $parafCompletionRate = $parafs->count() > 0 ? ($approvedParafs->count() / $parafs->count()) : 1;

                            $canDownloadPDF = $suratCuti->status === 'disetujui' ||
                                            ($allSignaturesComplete && $parafCompletionRate >= 0.8);

                            $overallCompletion = $disposisiList->count() > 0 ?
                                round(($disposisiList->where('status', 'sudah')->count() / $disposisiList->count()) * 100, 1) : 0;
                        @endphp

                        @if($canDownloadPDF)
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('surat-cuti.pdf', $suratCuti) }}"
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Download PDF
                                    @if($suratCuti->status !== 'disetujui')
                                        <span class="ml-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">
                                            {{ $overallCompletion }}% Complete
                                        </span>
                                    @endif
                                </a>

                                @if($suratCuti->status !== 'disetujui')
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        PDF tersedia dengan persetujuan fleksibel
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                PDF akan tersedia setelah semua tanda tangan disetujui
                                ({{ $approvedSignatures->count() }}/{{ $signatures->count() }} TTD,
                                {{ round($parafCompletionRate * 100, 1) }}% Paraf)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
