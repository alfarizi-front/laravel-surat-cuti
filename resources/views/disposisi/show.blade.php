<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Disposisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Debug Info (only in development) -->
            @if(config('app.debug'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">🐛 Debug Info</h4>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <p><strong>Disposisi ID:</strong> {{ $disposisi->id }}</p>
                        <p><strong>Status:</strong> {{ $disposisi->status }}</p>
                        <p><strong>User ID:</strong> {{ $disposisi->user_id }}</p>
                        <p><strong>Current User ID:</strong> {{ Auth::id() }}</p>
                        <p><strong>Tipe:</strong> {{ $disposisi->tipe_disposisi }}</p>
                        <p><strong>Jabatan:</strong> {{ $disposisi->jabatan }}</p>
                        <p><strong>Signature Setup:</strong> {{ Auth::user()->signature_setup_completed ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                            <strong>Info!</strong> {{ session('info') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Validation Errors:</strong>
                            <ul class="mt-2">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Header Info -->
                    <div class="mb-6 border-b pb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-2">Disposisi Surat Cuti #{{ $disposisi->suratCuti->id }}</h3>

                                <!-- Informasi Pemohon -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-3">
                                    <h4 class="text-sm font-semibold text-blue-900 mb-2">📋 Informasi Pemohon</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $disposisi->suratCuti->pengaju->nama }}
                                                </span>
                                                @if($disposisi->suratCuti->pengaju->nip)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        NIP: {{ $disposisi->suratCuti->pengaju->nip }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $disposisi->suratCuti->pengaju->jabatan }}</p>
                                            <p class="text-xs text-gray-500">{{ $disposisi->suratCuti->pengaju->unit_kerja }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Jenis Pegawai</p>
                                            <p class="text-sm text-gray-700">{{ $disposisi->suratCuti->pengaju->jenis_pegawai ?? 'ASN' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Email</p>
                                            <p class="text-sm text-gray-700">{{ $disposisi->suratCuti->pengaju->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($disposisi->tipe_disposisi === 'ttd') bg-red-100 text-red-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $disposisi->tipe_disposisi === 'ttd' ? '🖋️ Tanda Tangan' : '✍️ Paraf' }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">
                                    Sebagai: {{ $disposisi->jabatan }}
                                </p>


                            </div>
                        </div>
                    </div>

                    <!-- Detail Cuti -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Cuti</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jenis Cuti</dt>
                                    <dd class="text-sm text-gray-900">{{ $disposisi->suratCuti->jenisCuti->nama }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Cuti</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $disposisi->suratCuti->tanggal_awal->format('d/m/Y') }} - {{ $disposisi->suratCuti->tanggal_akhir->format('d/m/Y') }}
                                        ({{ $disposisi->suratCuti->jumlah_hari }} hari)
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alasan</dt>
                                    <dd class="text-sm text-gray-900">{{ $disposisi->suratCuti->alasan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Ajuan</dt>
                                    <dd class="text-sm text-gray-900">{{ $disposisi->suratCuti->tanggal_ajuan->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($disposisi->suratCuti->pengaju->jenis_pegawai === 'ASN')
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Informasi Tambahan</h4>
                                <dl class="space-y-2">
                                    @if($disposisi->suratCuti->alamat_selama_cuti)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Alamat Selama Cuti</dt>
                                            <dd class="text-sm text-gray-900">{{ $disposisi->suratCuti->alamat_selama_cuti }}</dd>
                                        </div>
                                    @endif
                                    @if($disposisi->suratCuti->kontak_darurat)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Kontak Darurat</dt>
                                            <dd class="text-sm text-gray-900">{{ $disposisi->suratCuti->kontak_darurat }}</dd>
                                        </div>
                                    @endif
                                    @if($disposisi->suratCuti->lampiran)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Lampiran</dt>
                                            <dd class="text-sm text-gray-900">
                                                <a href="{{ Storage::url($disposisi->suratCuti->lampiran) }}" target="_blank" 
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

                    <!-- Form Disposisi -->
                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Proses Disposisi</h4>

                        @if($disposisi->status === 'pending')
                            <form method="POST" action="{{ route('disposisi.process', $disposisi) }}" id="disposisiForm">
                            @csrf
                            @method('PATCH')

                            <!-- Debug CSRF Token -->
                            @if(config('app.debug'))
                                <div class="text-xs text-gray-500 mb-2">
                                    Debug: CSRF Token = {{ csrf_token() ? 'Present' : 'Missing' }}
                                </div>
                            @endif

                            <!-- Catatan -->
                            <div class="mb-4">
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea id="catatan" name="catatan" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('disposisi.pending') }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Kembali
                                </a>

                                <div class="space-x-3">
                                    <button type="submit" name="action" value="reject"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Yakin ingin menolak surat cuti ini?')">
                                        Tolak
                                    </button>
                                    <button type="submit" name="action" value="approve"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-all duration-200"
                                            onclick="return confirm('Yakin ingin menyetujui surat cuti ini?\n\nTindakan ini tidak dapat dibatalkan.')">
                                        @if($disposisi->tipe_disposisi === 'ttd')
                                            🖋️ Tanda Tangan & Setujui
                                        @else
                                            ✍️ Paraf & Setujui
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                        @else
                            <!-- Disposisi Already Processed -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">Disposisi Sudah Diproses</h3>
                                        <div class="mt-2 text-sm text-green-700">
                                            <p>
                                                Disposisi ini telah diproses pada {{ $disposisi->tanggal->format('d F Y H:i') }}.
                                                @if($disposisi->catatan)
                                                    <br><strong>Catatan:</strong> {{ $disposisi->catatan }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('disposisi.pending') }}"
                                               class="bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 px-4 rounded text-sm">
                                                Kembali ke Daftar Disposisi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Semua Disposisi -->
                    @php
                        $allDisposisi = $disposisi->suratCuti->disposisiCuti()->with('user')->get();
                        $completedDisposisi = $allDisposisi->where('status', 'sudah');
                        $pendingDisposisi = $allDisposisi->where('status', 'pending');
                    @endphp

                    <div class="border-t pt-6 mt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Status Semua Disposisi</h4>
                        
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Proses Disposisi Fleksibel</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Anda dapat memproses disposisi ini tanpa menunggu disposisi lain selesai. Surat akan disetujui setelah semua tanda tangan yang diperlukan lengkap.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($allDisposisi as $disp)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($disp->status === 'sudah')
                                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @elseif($disp->id === $disposisi->id)
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $disp->jabatan }} - {{ $disp->user->nama ?? 'User tidak ditemukan' }}
                                                @if($disp->id === $disposisi->id)
                                                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Anda</span>
                                                @endif
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-500">
                                                    {{ $disp->tipe_disposisi === 'ttd' ? 'Tanda Tangan' : 'Paraf' }}
                                                </span>
                                                @if($disp->status === 'sudah')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                                                @elseif($disp->id === $disposisi->id)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Sedang Diproses</span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Menunggu</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($disp->status === 'sudah')
                                            <p class="text-xs text-gray-500">
                                                Diproses: {{ $disp->tanggal->format('d/m/Y H:i') }}
                                            </p>
                                            @if($disp->catatan)
                                                <p class="text-sm text-gray-700 mt-1">{{ $disp->catatan }}</p>
                                            @endif
                                        @else
                                            <p class="text-xs text-gray-500">Belum diproses</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <strong>Progress:</strong> {{ $completedDisposisi->count() }} dari {{ $allDisposisi->count() }} disposisi telah selesai.
                                @if($pendingDisposisi->count() > 1)
                                    Masih ada {{ $pendingDisposisi->count() - 1 }} disposisi lain yang menunggu.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enhanced UX -->
    <script>
        // Smooth scroll to form
        function scrollToForm() {
            const formSection = document.querySelector('form');
            if (formSection) {
                formSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Focus on first input after scroll
                setTimeout(() => {
                    const firstInput = formSection.querySelector('input, textarea, select');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }, 500);
            }
        }



        // Auto-focus and enhanced form handling
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced form submission handling with debugging
            const form = document.querySelector('form[action*="disposisi.process"]');
            if (form) {
                console.log('Form found:', form);
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);

                // Check CSRF token
                const csrfToken = form.querySelector('input[name="_token"]');
                console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
                if (csrfToken) {
                    console.log('CSRF token value:', csrfToken.value);
                }

                form.addEventListener('submit', function(e) {
                    console.log('Form submission started');

                    // Log all form data
                    const formData = new FormData(form);
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }
                });

                // Add timeout detection
                form.addEventListener('submit', function(e) {
                    setTimeout(() => {
                        console.log('Form submission timeout check - still on disposisi page?');
                        if (window.location.href.includes('disposisi')) {
                            console.warn('Still on disposisi page after 5 seconds - possible submission issue');
                        }
                    }, 5000);
                });
            } else {
                console.error('Form not found!');
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + Enter to approve
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    const approveButton = document.querySelector('button[value="approve"]');
                    if (approveButton && !approveButton.disabled) {
                        e.preventDefault();
                        approveButton.click();
                    }
                }

                // Escape to scroll to top
                if (e.key === 'Escape') {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            // Add tooltips for keyboard shortcuts
            const approveButton = document.querySelector('button[value="approve"]');
            if (approveButton) {
                approveButton.title = 'Setujui (Ctrl+Enter)';
            }
        });
    </script>
</x-app-layout>