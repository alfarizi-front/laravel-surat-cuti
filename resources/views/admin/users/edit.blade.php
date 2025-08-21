<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        {{ __('Edit User') }}
                    </h2>
                    <p class="text-sm text-gray-600">Edit informasi user: {{ $user->nama }}</p>
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
                    <!-- Current User Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($user->nama, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-blue-800">{{ $user->nama }}</h3>
                                <p class="text-blue-600">{{ $user->jabatan }} - {{ $user->unit_kerja }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $user->jenis_pegawai }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('nama', $user->nama) }}"
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
                                           value="{{ old('nip', $user->nip) }}"
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
                                           value="{{ old('email', $user->email) }}"
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
                                            <input type="radio" name="jenis_pegawai" value="ASN" class="form-radio text-blue-600" {{ old('jenis_pegawai', $user->jenis_pegawai) == 'ASN' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm text-gray-700">ASN</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="jenis_pegawai" value="PPPK" class="form-radio text-blue-600" {{ old('jenis_pegawai', $user->jenis_pegawai) == 'PPPK' ? 'checked' : '' }} required>
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
                                            <option value="{{ $key }}" {{ old('unit_kerja', $user->unit_kerja) == $key ? 'selected' : '' }}>
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
                                            <option value="{{ $key }}" {{ old('jabatan', $user->jabatan) == $key ? 'selected' : '' }}>
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
                                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
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
                                            <i class="fas fa-lock mr-1"></i>Password Baru (Opsional)
                                        </label>
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>Konfirmasi Password
                                        </label>
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Ulangi password baru">
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
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
