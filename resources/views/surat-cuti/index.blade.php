<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(auth()->user()->role === 'admin')
                    {{ __('Semua Surat Cuti') }}
                @else
                    {{ __('Surat Cuti Saya') }}
                @endif
            </h2>
            <!-- Filter Controls -->
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" 
                           class="pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Cari...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>

                <div class="flex gap-2">
                    <input type="date" id="startDate" 
                           class="border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Dari Tanggal">
                    <input type="date" id="endDate" 
                           class="border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Sampai Tanggal">
                </div>

                <select id="statusFilter" class="border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            @if(auth()->user()->role === 'karyawan')
                <a href="{{ route('surat-cuti.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajukan Cuti Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($suratCuti->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="suratCutiTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                                            <div class="flex items-center">
                                                ID
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        @if(auth()->user()->role === 'admin')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="pengaju">
                                            <div class="flex items-center">
                                                Pengaju
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="jenis">
                                            <div class="flex items-center">
                                                Jenis Cuti
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="tanggal">
                                            <div class="flex items-center">
                                                Tanggal Cuti
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="status">
                                            <div class="flex items-center">
                                                Status
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="ajuan">
                                            <div class="flex items-center">
                                                Tanggal Ajuan
                                                <i class="fas fa-sort ml-1"></i>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratCuti as $surat)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $surat->id }}
                                            </td>
                                            @if(auth()->user()->role === 'admin')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $surat->pengaju->nama }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $surat->pengaju->jabatan }}
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ $surat->pengaju->unit_kerja }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $surat->jenisCuti->nama }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $surat->tanggal_awal->format('d/m/Y') }} - 
                                                    {{ $surat->tanggal_akhir->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ({{ $surat->jumlah_hari }} hari)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($surat->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($surat->status === 'proses') bg-yellow-100 text-yellow-800
                                                    @elseif($surat->status === 'disetujui') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($surat->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($surat->tanggal_ajuan)
                                                    {{ $surat->tanggal_ajuan->format('d/m/Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('surat-cuti.show', $surat) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                                
                                                @if($surat->status === 'disetujui')
                                                    <a href="{{ route('surat-cuti.download-pdf', $surat) }}"
                                                       class="ml-3 inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition-colors duration-200"
                                                       title="Download PDF Surat Cuti">
                                                        <i class="fas fa-download mr-1"></i>
                                                        Download PDF
                                                    </a>
                                                @endif

                                                @if($surat->pengaju_id === auth()->id() && $surat->status === 'draft')
                                                    <form method="POST" action="{{ route('surat-cuti.submit', $surat) }}" class="inline ml-3">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="text-blue-600 hover:text-blue-900"
                                                                onclick="return confirm('Yakin ingin submit surat cuti ini?')">
                                                            Submit
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $suratCuti->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada surat cuti</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(auth()->user()->role === 'karyawan')
                                    Mulai dengan mengajukan surat cuti pertama Anda.
                                @else
                                    Belum ada surat cuti yang diajukan.
                                @endif
                            </p>
                            @if(auth()->user()->role === 'karyawan')
                                <div class="mt-6">
                                    <a href="{{ route('surat-cuti.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Ajukan Cuti Baru
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        const table = document.getElementById('suratCutiTable');
        if (!table) return;

        const searchInput = document.getElementById('searchInput');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const statusFilter = document.getElementById('statusFilter');
        const isAdmin = document.querySelector('th[data-sort="pengaju"]') !== null;
        let currentSort = { column: '', direction: 'asc' };

        // Helper function untuk parsing tanggal
        function parseDate(dateStr) {
            if (!dateStr || dateStr === '-') return new Date(0);
            const parts = dateStr.trim().split(/[/ :]/).map(p => parseInt(p));
            if (parts.length >= 3) {
                return new Date(parts[2], parts[1] - 1, parts[0], parts[3] || 0, parts[4] || 0);
            }
            return new Date(0);
        }

        // Get cell value for sorting
        function getCellValue(row, column) {
            const columnIndices = {
                'id': 0,
                'pengaju': isAdmin ? 1 : null,
                'jenis': isAdmin ? 2 : 1,
                'tanggal': isAdmin ? 3 : 2,
                'status': isAdmin ? 4 : 3,
                'ajuan': isAdmin ? 5 : 4
            };

            const idx = columnIndices[column];
            if (idx === null) return '';
            
            const cell = row.cells[idx];
            if (!cell) return '';

            switch(column) {
                case 'id':
                    return parseInt(cell.textContent.replace(/\D/g, '')) || 0;
                case 'pengaju':
                    return cell.querySelector('.text-sm.font-medium')?.textContent.trim() || '';
                case 'tanggal': {
                    const dateText = cell.querySelector('.text-sm')?.textContent.split('-')[0].trim();
                    return parseDate(dateText);
                }
                case 'status':
                    return cell.querySelector('span')?.textContent.trim() || '';
                case 'ajuan': {
                    const dateText = cell.textContent.trim();
                    return parseDate(dateText === '-' ? '01/01/1970' : dateText);
                }
                default:
                    return cell.textContent.trim().toLowerCase();
            }
        }

        // Sort table function
        function sortTable(column) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = column;
                currentSort.direction = 'asc';
            }

            rows.sort((a, b) => {
                const aValue = getCellValue(a, column);
                const bValue = getCellValue(b, column);

                if (aValue === bValue) return 0;
                
                const modifier = currentSort.direction === 'asc' ? 1 : -1;
                
                if (aValue instanceof Date && bValue instanceof Date) {
                    return (aValue - bValue) * modifier;
                }
                
                if (typeof aValue === 'number' && typeof bValue === 'number') {
                    return (aValue - bValue) * modifier;
                }
                
                return aValue < bValue ? -1 * modifier : 1 * modifier;
            });

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));

            updateSortIcons(column);
        }

        // Update sort icons
        function updateSortIcons(activeColumn) {
            table.querySelectorAll('th[data-sort]').forEach(th => {
                const icon = th.querySelector('i');
                if (th.dataset.sort === activeColumn) {
                    icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} ml-1`;
                } else {
                    icon.className = 'fas fa-sort ml-1';
                }
            });
        }

        // Filter table function
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const startDateVal = startDate.value;
            const endDateVal = endDate.value;
            const statusVal = statusFilter.value.toLowerCase();

            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const dateCell = row.cells[isAdmin ? 3 : 2];
                const statusCell = row.cells[isAdmin ? 4 : 3];
                
                const dateText = dateCell.querySelector('.text-sm')?.textContent.split('-')[0].trim() || '';
                const status = statusCell.querySelector('span')?.textContent.trim().toLowerCase() || '';
                
                let dateMatch = true;
                if (startDateVal && endDateVal) {
                    const rowDate = parseDate(dateText);
                    const startDateTime = new Date(startDateVal);
                    const endDateTime = new Date(endDateVal);
                    endDateTime.setHours(23, 59, 59, 999); // Include the entire end date
                    dateMatch = rowDate >= startDateTime && rowDate <= endDateTime;
                }

                const statusMatch = statusVal === '' || status.includes(statusVal);
                const textMatch = searchTerm === '' || text.includes(searchTerm);

                row.style.display = (textMatch && dateMatch && statusMatch) ? '' : 'none';
            });
        }

        // Add event listeners
        table.querySelectorAll('th[data-sort]').forEach(th => {
            th.addEventListener('click', () => sortTable(th.dataset.sort));
        });

        searchInput.addEventListener('input', filterTable);
        startDate.addEventListener('change', filterTable);
        endDate.addEventListener('change', filterTable);
        statusFilter.addEventListener('change', filterTable);
    });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Hover effects */
        #suratCutiTable tbody tr:hover {
            background-color: rgba(249, 250, 251, 1);
        }

        th[data-sort] {
            transition: background-color 0.2s;
        }

        th[data-sort]:hover {
            background-color: rgba(243, 244, 246, 1);
            cursor: pointer;
        }

        .rounded-full {
            transition: all 0.2s;
        }

        .text-blue-600:hover, .text-green-600:hover, .text-indigo-600:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }
    </style>
    @endpush
</x-app-layout>
