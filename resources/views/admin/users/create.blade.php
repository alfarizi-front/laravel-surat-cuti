<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        {{ __('Tambah User Baru') }}
                    </h2>
                    <p class="text-sm text-gray-600">Buat akun untuk user disposisi surat cuti</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8">
                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Panduan Pembuatan User</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Role Atasan:</strong> Pilih role sesuai dengan posisi dalam alur disposisi</li>
                                        <li><strong>KADIN:</strong> Persetujuan final semua surat cuti</li>
                                        <li><strong>Sekdin:</strong> Disposisi surat cuti dari bidang</li>
                                        <li><strong>Kasubag:</strong> Disposisi surat cuti dari sekretariat</li>
                                        <li><strong>Kepala:</strong> Disposisi surat cuti dari unit kerja masing-masing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Personal Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Informasi Personal
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama') }}"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('nama')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIP -->
                                <div>
                                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-id-card mr-1"></i>NIP (Opsional)
                                    </label>
                                    <input type="text" 
                                           id="nip" 
                                           name="nip" 
                                           value="{{ old('nip') }}"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Nomor Induk Pegawai">
                                    @error('nip')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-1"></i>Email
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           placeholder="nama@dinkes.go.id"
                                           required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Pegawai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user-tag mr-1"></i>Jenis Pegawai
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="jenis_pegawai" value="ASN" class="form-radio text-blue-600" {{ old('jenis_pegawai') == 'ASN' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm text-gray-700">ASN</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="jenis_pegawai" value="PPPK" class="form-radio text-blue-600" {{ old('jenis_pegawai') == 'PPPK' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm text-gray-700">PPPK</span>
                                        </label>
                                    </div>
                                    @error('jenis_pegawai')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Work Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-briefcase mr-2 text-green-500"></i>
                                Informasi Pekerjaan
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Unit Kerja -->
                                <div>
                                    <label for="unit_kerja" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-building mr-1"></i>Unit Kerja
                                    </label>
                                    <select id="unit_kerja" 
                                            name="unit_kerja" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                            required>
                                        <option value="">Pilih Unit Kerja</option>
                                        @foreach($unitKerjaOptions as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_kerja') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_kerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jabatan -->
                                <div>
                                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user-tie mr-1"></i>Jabatan
                                    </label>
                                    <select id="jabatan" 
                                            name="jabatan" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                            required>
                                        <option value="">Pilih Jabatan</option>
                                        @foreach($jabatanOptions as $key => $value)
                                            <option value="{{ $key }}" {{ old('jabatan') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jabatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-cog mr-2 text-purple-500"></i>
                                Pengaturan Sistem
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-shield-alt mr-1"></i>Role Sistem
                                    </label>
                                    <select id="role" 
                                            name="role" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                            required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roleOptions as $key => $value)
                                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>Password
                                        </label>
                                        <input type="password"
                                               id="password"
                                               name="password"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Minimal 8 karakter"
                                               required>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>Konfirmasi Password
                                        </label>
                                        <input type="password"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Ulangi password"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
