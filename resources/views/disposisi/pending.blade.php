<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Disposisi Pending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($pendingDisposisi->count() > 0)
                        <!-- Search and Filter Section -->
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Disposisi Pending</h3>
                            <div class="flex items-center space-x-3">
                                <!-- Search Box -->
                                <div class="relative">
                                    <input type="text"
                                           id="searchDisposisi"
                                           placeholder="Cari nama atau NIP pemohon..."
                                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm w-64">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                                <!-- Count Badge -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span id="totalCount">{{ $pendingDisposisi->count() }}</span> -disposisi
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pemohon
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Detail Cuti
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe Disposisi
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Ajuan
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingDisposisi as $disposisi)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $disposisi->suratCuti->pengaju->nama }}
                                                            </div>
                                                            @if($disposisi->suratCuti->pengaju->nip)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    NIP: {{ $disposisi->suratCuti->pengaju->nip }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $disposisi->suratCuti->pengaju->jabatan }}
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ $disposisi->suratCuti->pengaju->unit_kerja }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $disposisi->suratCuti->jenisCuti->nama }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $disposisi->suratCuti->tanggal_awal->format('d/m/Y') }} -
                                                    {{ $disposisi->suratCuti->tanggal_akhir->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $disposisi->suratCuti->jumlah_hari }} hari |
                                                    Alasan: {{ \Illuminate\Support\Str::limit($disposisi->suratCuti->alasan, 30) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $disposisi->tipe_disposisi === 'ttd' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ $disposisi->tipe_disposisi === 'ttd' ? '🖋️ Tanda Tangan' : '✍️ Paraf' }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Sebagai {{ $disposisi->jabatan }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $disposisi->suratCuti->tanggal_ajuan->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('disposisi.show', $disposisi) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Proses
                                                </a>
                                                <a href="{{ route('surat-cuti.show', $disposisi->suratCuti) }}" 
                                                   class="ml-3 text-gray-600 hover:text-gray-900">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $pendingDisposisi->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada disposisi pending</h3>
                            <p class="mt-1 text-sm text-gray-500">Saat ini tidak ada surat cuti yang menunggu disposisi Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchDisposisi');
            const totalCountSpan = document.getElementById('totalCount');
            const tableRows = document.querySelectorAll('tbody tr');
            const originalCount = tableRows.length;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    tableRows.forEach(function(row) {
                        const pemohonCell = row.querySelector('td:first-child');
                        if (pemohonCell) {
                            const nama = pemohonCell.textContent.toLowerCase();
                            const isVisible = nama.includes(searchTerm);

                            row.style.display = isVisible ? '' : 'none';
                            if (isVisible) visibleCount++;
                        }
                    });

                    // Update count
                    if (totalCountSpan) {
                        totalCountSpan.textContent = visibleCount;
                    }

                    // Show/hide "no results" message
                    const tbody = document.querySelector('tbody');
                    let noResultsRow = document.getElementById('noResultsRow');

                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.id = 'noResultsRow';
                            noResultsRow.innerHTML = `
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-search text-gray-400 text-2xl mb-2"></i>
                                    <p>Tidak ditemukan disposisi dengan kata kunci "<strong>${searchTerm}</strong>"</p>
                                    <p class="text-sm">Coba gunakan nama lengkap atau NIP pemohon</p>
                                </td>
                            `;
                            tbody.appendChild(noResultsRow);
                        }
                    } else if (noResultsRow) {
                        noResultsRow.remove();
                    }
                });

                // Clear search on Escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur();
                    }
                });
            }
        });
    </script>
</x-app-layout>
