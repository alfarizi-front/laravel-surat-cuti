<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setup Tanda Tangan - Wajib') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-3xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-800">Selamat Datang, {{ $user->nama }}!</h3>
                        <p class="text-blue-700 mt-1">
                            Untuk dapat menggunakan sistem surat cuti, Anda harus setup tanda tangan terlebih dahulu.
                            Tanda tangan ini akan digunakan pada semua surat yang Anda buat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Informasi Akun Anda</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                    <p class="text-gray-900">{{ $user->nama }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">NIP</label>
                                    <p class="text-gray-900">{{ $user->nip ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Jabatan</label>
                                    <p class="text-gray-900">{{ $user->jabatan }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Unit Kerja</label>
                                    <p class="text-gray-900">{{ $user->unit_kerja }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Jenis Pegawai</label>
                                    <p class="text-gray-900">
                                        <span class="px-3 py-1 bg-{{ $user->jenis_pegawai === 'ASN' ? 'blue' : 'green' }}-100 text-{{ $user->jenis_pegawai === 'ASN' ? 'blue' : 'green' }}-800 rounded-full text-sm font-medium">
                                            {{ $user->jenis_pegawai }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Role</label>
                                    <p class="text-gray-900">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Setup Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-signature mr-2 text-blue-500"></i>
                        Setup Tanda Tangan
                    </h3>
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('signature.complete-setup') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Tanda Tangan (Wajib) -->
                        <div>
                            <label for="signature" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-signature mr-1"></i>
                                File Tanda Tangan <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="signature" 
                                   name="signature" 
                                   accept="image/*" 
                                   required
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">
                                Format: PNG, JPG, JPEG. Maksimal 1MB. Background transparan disarankan untuk hasil terbaik.
                            </p>
                        </div>

                        <!-- Cap Stempel (Khusus KADIN) -->
                        @if($user->role === 'kadin' || str_contains(strtolower($user->jabatan), 'kepala dinas'))
                        <div class="border-t pt-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="font-medium text-yellow-800 mb-3 flex items-center">
                                    <i class="fas fa-crown mr-2"></i>
                                    Setup Khusus Kepala Dinas
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="cap_stempel" class="block text-sm font-medium text-yellow-700 mb-2">
                                            <i class="fas fa-stamp mr-1"></i>
                                            Cap/Stempel Dinas (Opsional)
                                        </label>
                                        <input type="file" 
                                               id="cap_stempel" 
                                               name="cap_stempel" 
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                        <p class="mt-1 text-xs text-yellow-600">
                                            Format: PNG, JPG, JPEG. Maksimal 1MB. Background transparan wajib untuk cap stempel.
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="gunakan_cap" 
                                                   value="1"
                                                   class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-yellow-700 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Gunakan cap/stempel pada surat resmi
                                            </span>
                                        </label>
                                        <p class="mt-1 text-xs text-yellow-600 ml-6">
                                            Cap akan ditampilkan di atas tanda tangan pada surat PDF
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Instructions -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-3">
                                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                                Tips Upload Tanda Tangan
                            </h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Gunakan tanda tangan asli yang sudah di-scan</li>
                                <li>• Format PNG dengan background transparan memberikan hasil terbaik</li>
                                <li>• Pastikan tanda tangan terlihat jelas dan tidak buram</li>
                                <li>• Ukuran file maksimal 1MB</li>
                                <li>• Tanda tangan akan digunakan pada semua surat yang Anda buat</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Selesaikan Setup & Mulai Menggunakan Sistem
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <div>
                        <p class="text-sm text-red-700">
                            <strong>Penting:</strong> Setup tanda tangan ini wajib dilakukan sebelum Anda dapat mengajukan surat cuti atau menggunakan fitur lainnya dalam sistem.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
