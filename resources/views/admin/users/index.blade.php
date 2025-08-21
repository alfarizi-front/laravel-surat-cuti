<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        {{ __('Kelola User') }}
                    </h2>
                    <p class="text-sm text-gray-600">Manajemen akun user sistem</p>
                </div>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah User Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total User</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $users->total() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Admin</p>
                                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Atasan</p>
                                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::whereIn('role', ['kadin', 'sekdin', 'kasubag', 'kepala'])->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-tie text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Karyawan</p>
                                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::where('role', 'karyawan')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar User</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $users->count() }} dari {{ $users->total() }} user</span>
                        </div>
                    </div>
                </div>

                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan & Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($user->nama, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    @if($user->nip)
                                                        <div class="text-xs text-gray-400">NIP: {{ $user->nip }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->jabatan }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->unit_kerja }}</div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->jenis_pegawai === 'ASN' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $user->jenis_pegawai }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-red-100 text-red-800',
                                                    'kadin' => 'bg-purple-100 text-purple-800',
                                                    'sekdin' => 'bg-indigo-100 text-indigo-800',
                                                    'kasubag' => 'bg-yellow-100 text-yellow-800',
                                                    'kepala' => 'bg-orange-100 text-orange-800',
                                                    'karyawan' => 'bg-gray-100 text-gray-800',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ strtoupper($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                <span class="text-sm text-gray-900">Aktif</span>
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-edit text-xs mr-1"></i>
                                                    Edit
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors duration-200"
                                                                onclick="return confirm('Yakin ingin menghapus user {{ $user->nama }}?')">
                                                            <i class="fas fa-trash text-xs mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada user</h3>
                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan user pertama untuk sistem.</p>
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah User
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
