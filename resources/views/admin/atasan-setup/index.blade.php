<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Setup Atasan & Template Surat') }}
            </h2>
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

            <!-- Info Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Pengaturan Alur Persetujuan Surat Cuti
                </h3>
                <p class="text-blue-700 text-sm">
                    Atur siapa saja atasan yang perlu memberikan tanda tangan untuk setiap jenis pegawai (ASN/PPPK). 
                    Sistem akan otomatis menggunakan template PDF yang berbeda berdasarkan pengaturan ini.
                </p>
            </div>

            <!-- ASN Setup -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                            Setup Atasan untuk ASN
                        </h3>
                        <div class="space-x-2">
                            <button onclick="openAddModal('ASN')" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                <i class="fas fa-plus mr-1"></i>Tambah Atasan
                            </button>
                            <form method="POST" action="{{ route('admin.atasan-setup.reset-default') }}" class="inline">
                                @csrf
                                <input type="hidden" name="jenis_pegawai" value="ASN">
                                <button type="submit" 
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm"
                                        onclick="return confirm('Reset ke pengaturan default?')">
                                    <i class="fas fa-undo mr-1"></i>Reset Default
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">TTD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($setupASN as $setup)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setup->urutan_disposisi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setup->nama_jabatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ strtoupper($setup->level_atasan) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($setup->perlu_tanda_tangan)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($setup->perlu_cap_stempel)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.atasan-setup.toggle', $setup->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2 py-1 rounded-full text-xs {{ $setup->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $setup->aktif ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editSetup({{ $setup->id }}, '{{ $setup->level_atasan }}', '{{ $setup->nama_jabatan }}', {{ $setup->perlu_tanda_tangan ? 'true' : 'false' }}, {{ $setup->perlu_cap_stempel ? 'true' : 'false' }}, {{ $setup->urutan_disposisi }}, '{{ $setup->keterangan }}')" 
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.atasan-setup.destroy', $setup->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada setup atasan untuk ASN
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PPPK Setup -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-user-check mr-2 text-green-500"></i>
                            Setup Atasan untuk PPPK
                        </h3>
                        <div class="space-x-2">
                            <button onclick="openAddModal('PPPK')" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                <i class="fas fa-plus mr-1"></i>Tambah Atasan
                            </button>
                            <form method="POST" action="{{ route('admin.atasan-setup.reset-default') }}" class="inline">
                                @csrf
                                <input type="hidden" name="jenis_pegawai" value="PPPK">
                                <button type="submit" 
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm"
                                        onclick="return confirm('Reset ke pengaturan default?')">
                                    <i class="fas fa-undo mr-1"></i>Reset Default
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">TTD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($setupPPPK as $setup)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setup->urutan_disposisi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setup->nama_jabatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ strtoupper($setup->level_atasan) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($setup->perlu_tanda_tangan)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($setup->perlu_cap_stempel)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.atasan-setup.toggle', $setup->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2 py-1 rounded-full text-xs {{ $setup->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $setup->aktif ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editSetup({{ $setup->id }}, '{{ $setup->level_atasan }}', '{{ $setup->nama_jabatan }}', {{ $setup->perlu_tanda_tangan ? 'true' : 'false' }}, {{ $setup->perlu_cap_stempel ? 'true' : 'false' }}, {{ $setup->urutan_disposisi }}, '{{ $setup->keterangan }}')" 
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.atasan-setup.destroy', $setup->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada setup atasan untuk PPPK
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Preview Templates -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-eye mr-2 text-purple-500"></i>
                        Preview Template PDF
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.atasan-setup.preview-template', 'ASN') }}" 
                           target="_blank"
                           class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-file-pdf text-2xl text-blue-500 mr-4"></i>
                            <div>
                                <div class="font-semibold text-blue-700">Template ASN</div>
                                <div class="text-sm text-blue-600">Preview surat untuk pegawai ASN</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.atasan-setup.preview-template', 'PPPK') }}" 
                           target="_blank"
                           class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-file-pdf text-2xl text-green-500 mr-4"></i>
                            <div>
                                <div class="font-semibold text-green-700">Template PPPK</div>
                                <div class="text-sm text-green-600">Preview surat untuk pegawai PPPK</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="setupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Tambah Setup Atasan</h3>
                
                <form id="setupForm" method="POST" action="{{ route('admin.atasan-setup.store') }}">
                    @csrf
                    <input type="hidden" id="setupId" name="setup_id">
                    <input type="hidden" id="jenisPegawai" name="jenis_pegawai">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level Atasan</label>
                            <select id="levelAtasan" name="level_atasan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="sekdin">Sekretaris Dinas</option>
                                <option value="kadin">Kepala Dinas</option>
                                <option value="kepala">Kepala Bidang/Seksi</option>
                                <option value="kasubag">Kepala Sub Bagian</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Jabatan</label>
                            <input type="text" id="namaJabatan" name="nama_jabatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Urutan Disposisi</label>
                            <input type="number" id="urutanDisposisi" name="urutan_disposisi" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="perluTandaTangan" name="perlu_tanda_tangan" value="1" checked class="rounded border-gray-300">
                                <span class="ml-2 text-sm">Perlu Tanda Tangan</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" id="perluCapStempel" name="perlu_cap_stempel" value="1" class="rounded border-gray-300">
                                <span class="ml-2 text-sm">Perlu Cap Stempel</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddModal(jenisPegawai) {
            document.getElementById('modalTitle').textContent = 'Tambah Setup Atasan ' + jenisPegawai;
            document.getElementById('setupForm').action = '{{ route("admin.atasan-setup.store") }}';
            document.getElementById('setupForm').method = 'POST';
            document.getElementById('jenisPegawai').value = jenisPegawai;
            document.getElementById('setupId').value = '';
            
            // Reset form
            document.getElementById('levelAtasan').value = '';
            document.getElementById('namaJabatan').value = '';
            document.getElementById('urutanDisposisi').value = '1';
            document.getElementById('perluTandaTangan').checked = true;
            document.getElementById('perluCapStempel').checked = false;
            document.getElementById('keterangan').value = '';
            
            document.getElementById('setupModal').classList.remove('hidden');
        }
        
        function editSetup(id, level, nama, ttd, cap, urutan, keterangan) {
            document.getElementById('modalTitle').textContent = 'Edit Setup Atasan';
            document.getElementById('setupForm').action = '{{ route("admin.atasan-setup.update", ":id") }}'.replace(':id', id);
            
            // Add method spoofing for PUT
            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('setupForm').appendChild(methodInput);
            }
            
            document.getElementById('setupId').value = id;
            document.getElementById('levelAtasan').value = level;
            document.getElementById('namaJabatan').value = nama;
            document.getElementById('urutanDisposisi').value = urutan;
            document.getElementById('perluTandaTangan').checked = ttd;
            document.getElementById('perluCapStempel').checked = cap;
            document.getElementById('keterangan').value = keterangan;
            
            document.getElementById('setupModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('setupModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('setupModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
