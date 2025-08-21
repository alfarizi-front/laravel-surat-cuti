<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Manajemen Cap & Tanda Tangan (KADIN & Kepala Puskesmas)') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.cap-stempel.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Upload Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Current User Quick Upload -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Upload Cepat Pimpinan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current User Signature -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-3">{{ $currentUser->nama }}</h4>
                            <div class="space-y-3">
                                @if($currentUser->tanda_tangan)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $currentUser->tanda_tangan) }}" 
                                             alt="Signature" 
                                             class="max-h-12 border rounded bg-gray-50 p-1">
                                        <span class="text-green-600 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Tanda tangan OK
                                        </span>
                                    </div>
                                @else
                                    <div class="text-red-600 text-sm">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Belum ada tanda tangan
                                    </div>
                                @endif

                                @if($currentUser->cap_stempel)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $currentUser->cap_stempel) }}" 
                                             alt="Cap" 
                                             class="max-h-12 border rounded bg-gray-50 p-1">
                                        <span class="text-green-600 text-sm">
                                            <i class="fas fa-stamp mr-1"></i>
                                            Cap stempel OK
                                            @if($currentUser->gunakan_cap)
                                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aktif</span>
                                            @else
                                                <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Nonaktif</span>
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <div class="text-orange-600 text-sm">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Belum ada cap stempel
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.cap-stempel.create', ['user_id' => $currentUser->id]) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    <i class="fas fa-upload mr-1"></i>
                                    Upload/Update
                                </a>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <h4 class="font-medium mb-3">Statistik Sistem</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Total User Pimpinan:</span>
                                    <span class="font-semibold">{{ $users->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Punya Tanda Tangan:</span>
                                    <span class="font-semibold text-green-600">{{ $users->whereNotNull('tanda_tangan')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Punya Cap Stempel:</span>
                                    <span class="font-semibold text-blue-600">{{ $users->whereNotNull('cap_stempel')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Cap Aktif:</span>
                                    <span class="font-semibold text-purple-600">{{ $users->where('gunakan_cap', true)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Users Management -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-users-cog mr-2 text-green-500"></i>
                        Manajemen Cap & Tanda Tangan Pimpinan
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanda Tangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cap Stempel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Cap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->jabatan }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @php
                                                        $isKadin = ($user->role === 'kadin') || (stripos($user->jabatan, 'Kepala Dinas') !== false);
                                                        $isKapus = ($user->role === 'kapus') || (stripos($user->jabatan, 'Kepala Puskesmas') !== false);
                                                    @endphp
                                                    @if($isKadin)
                                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                                            <i class="fas fa-crown mr-1"></i>KADIN
                                                        </span>
                                                    @elseif($isKapus)
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                            <i class="fas fa-hospital mr-1"></i>Kepala Puskesmas
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            <i class="fas fa-user mr-1"></i>Pimpinan
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->tanda_tangan)
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ asset('storage/' . $user->tanda_tangan) }}" 
                                                     alt="Signature" 
                                                     class="max-h-8 border rounded bg-gray-50 p-1">
                                                <span class="text-green-600 text-xs">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-red-600 text-xs">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Belum ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->cap_stempel)
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ asset('storage/' . $user->cap_stempel) }}" 
                                                     alt="Cap" 
                                                     class="max-h-8 border rounded bg-gray-50 p-1">
                                                <span class="text-green-600 text-xs">
                                                    <i class="fas fa-stamp"></i>
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-orange-600 text-xs">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Belum ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->cap_stempel)
                                            <form method="POST" action="{{ route('admin.cap-stempel.toggle', $user->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1 rounded-full text-xs font-medium {{ $user->gunakan_cap ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    @if($user->gunakan_cap)
                                                        <i class="fas fa-check mr-1"></i>Aktif
                                                    @else
                                                        <i class="fas fa-times mr-1"></i>Nonaktif
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.cap-stempel.create', ['user_id' => $user->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                            
                                            @if($user->cap_stempel)
                                                <a href="{{ route('admin.cap-stempel.preview', $user->id) }}" 
                                                   class="text-green-600 hover:text-green-900"
                                                   target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <form method="POST" action="{{ route('admin.cap-stempel.destroy', $user->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="type" value="cap">
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Yakin ingin menghapus cap stempel?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada user pimpinan ditemukan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bulk Upload Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-upload mr-2 text-purple-500"></i>
                        Upload Cap Stempel Massal
                    </h3>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-700 mb-4">
                            Upload satu cap stempel untuk digunakan oleh beberapa user sekaligus. 
                            Berguna untuk cap stempel yang sama untuk semua pimpinan.
                        </p>
                        
                        <form method="POST" action="{{ route('admin.cap-stempel.bulk-upload') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cap_stempel_bulk" class="block text-sm font-medium text-gray-700 mb-2">
                                        File Cap Stempel
                                    </label>
                                    <input type="file" 
                                           id="cap_stempel_bulk" 
                                           name="cap_stempel" 
                                           accept="image/*" 
                                           required
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih User
                                    </label>
                                    <div class="space-y-2 max-h-32 overflow-y-auto border rounded p-2">
                                        @foreach($users as $user)
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       name="user_ids[]" 
                                                       value="{{ $user->id }}"
                                                       class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm">{{ $user->nama }} ({{ $user->jabatan }})</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" 
                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-upload mr-2"></i>
                                    Upload untuk User Terpilih
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Panduan Upload Cap & Tanda Tangan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">
                                <i class="fas fa-signature mr-1"></i>
                                Tanda Tangan
                            </h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Format: PNG, JPG, JPEG</li>
                                <li>• Ukuran maksimal: 1MB</li>
                                <li>• Background transparan disarankan</li>
                                <li>• Ukuran optimal: 300x150 pixel</li>
                                <li>• Scan dengan resolusi tinggi</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">
                                <i class="fas fa-stamp mr-1"></i>
                                Cap Stempel
                            </h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Format: PNG, JPG, JPEG</li>
                                <li>• Ukuran maksimal: 1MB</li>
                                <li>• Background transparan wajib</li>
                                <li>• Ukuran optimal: 200x100 pixel</li>
                                <li>• Warna hitam untuk kontras terbaik</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-lightbulb mr-1"></i>
                            <strong>Tips:</strong> Cap stempel akan ditampilkan di atas tanda tangan pada surat resmi. 
                            Pastikan cap tidak terlalu besar agar tidak menutupi tanda tangan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
