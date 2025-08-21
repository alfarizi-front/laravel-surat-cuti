<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Upload Cap & Tanda Tangan') }}
                @if($user)
                    - {{ $user->nama }}
                @endif
            </h2>
            <a href="{{ route('admin.cap-stempel.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- User Selection (if no specific user) -->
            @if(!$user)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Pilih User</h3>
                        
                        <form method="GET" action="{{ route('admin.cap-stempel.create') }}">
                            <div class="flex space-x-4">
                                <select name="user_id" 
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="">Pilih User...</option>
                                    @foreach(\App\Models\User::where(function($q){ $q->where('role','kadin')->orWhere('jabatan','like','%Kepala Dinas%'); })
                                        ->orWhere(function($q){ $q->where('role','kapus')->orWhere('jabatan','like','%Kepala Puskesmas%'); })
                                        ->orderBy('nama')->get() as $u)
                                        <option value="{{ $u->id }}">{{ $u->nama }} - {{ $u->jabatan }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Pilih
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if($user)
                <!-- Current Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Status Saat Ini - {{ $user->nama }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Signature -->
                            <div class="border rounded-lg p-4">
                                <h4 class="font-medium mb-3 flex items-center">
                                    <i class="fas fa-signature mr-2 text-blue-500"></i>
                                    Tanda Tangan
                                </h4>
                                @if($user->tanda_tangan)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $user->tanda_tangan) }}" 
                                             alt="Current Signature" 
                                             class="max-h-20 mx-auto border rounded bg-gray-50 p-2 mb-2">
                                        <p class="text-green-600 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Sudah diupload
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-400">
                                        <i class="fas fa-signature text-4xl mb-2"></i>
                                        <p class="text-sm">Belum ada tanda tangan</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Current Cap -->
                            <div class="border rounded-lg p-4">
                                <h4 class="font-medium mb-3 flex items-center">
                                    <i class="fas fa-stamp mr-2 text-green-500"></i>
                                    Cap Stempel
                                </h4>
                                @if($user->cap_stempel)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $user->cap_stempel) }}" 
                                             alt="Current Cap" 
                                             class="max-h-20 mx-auto border rounded bg-gray-50 p-2 mb-2">
                                        <p class="text-green-600 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Sudah diupload
                                            @if($user->gunakan_cap)
                                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aktif</span>
                                            @else
                                                <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Nonaktif</span>
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-400">
                                        <i class="fas fa-stamp text-4xl mb-2"></i>
                                        <p class="text-sm">Belum ada cap stempel</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-upload mr-2 text-blue-500"></i>
                            Upload File Baru
                        </h3>
                        
                        <form method="POST" action="{{ route('admin.cap-stempel.store') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            
                            <!-- Tanda Tangan -->
                            <div>
                                <label for="tanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-signature mr-1"></i>
                                    File Tanda Tangan (Opsional)
                                </label>
                                <input type="file" 
                                       id="tanda_tangan" 
                                       name="tanda_tangan" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: PNG, JPG, JPEG. Maksimal 1MB. Kosongkan jika tidak ingin mengubah.
                                </p>
                            </div>

                            <!-- Cap Stempel -->
                            <div>
                                <label for="cap_stempel" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-stamp mr-1"></i>
                                    File Cap Stempel (Opsional)
                                </label>
                                <input type="file" 
                                       id="cap_stempel" 
                                       name="cap_stempel" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: PNG, JPG, JPEG. Maksimal 1MB. Background transparan disarankan.
                                </p>
                            </div>

                            <!-- Gunakan Cap Checkbox -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="gunakan_cap" 
                                           value="1"
                                           {{ $user->gunakan_cap ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Gunakan cap stempel pada surat resmi
                                    </span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500 ml-6">
                                    Cap akan ditampilkan di atas tanda tangan pada PDF surat cuti
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex space-x-4">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                                
                                <a href="{{ route('admin.cap-stempel.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Section -->
                @if($user->tanda_tangan || $user->cap_stempel)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-eye mr-2 text-purple-500"></i>
                                Preview Tampilan di PDF
                            </h3>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50">
                                <div class="inline-block">
                                    <p class="text-sm text-gray-600 mb-4">Kepala Dinas Kesehatan Daerah<br>Kabupaten Purworejo</p>
                                    
                                    <!-- Preview Cap + Signature -->
                                    <div class="space-y-2">
                                        @if($user->cap_stempel && $user->gunakan_cap)
                                            <div>
                                                <img src="{{ asset('storage/' . $user->cap_stempel) }}" 
                                                     alt="Preview Cap" 
                                                     class="max-h-16 mx-auto">
                                            </div>
                                        @endif
                                        
                                        @if($user->tanda_tangan)
                                            <div>
                                                <img src="{{ asset('storage/' . $user->tanda_tangan) }}" 
                                                     alt="Preview Signature" 
                                                     class="max-h-16 mx-auto">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mt-4">
                                        <strong>{{ $user->nama }}</strong><br>
                                        @if($user->nip)
                                            NIP. {{ $user->nip }}
                                        @else
                                            {{ $user->jabatan }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                Preview tampilan cap dan tanda tangan pada surat PDF
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
