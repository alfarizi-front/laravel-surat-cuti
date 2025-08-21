@extends('layouts.app')

@section('title', 'Admin Dashboard - Bulk Approval')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <h1 class="text-2xl font-bold mb-2">🚀 Admin Dashboard - Bulk Operations</h1>
            <p class="text-blue-100">Fitur khusus untuk debugging - approve/reject semua surat cuti sekaligus</p>
        </div>

        <div class="p-6">
                    
            <!-- Test Button for Debugging -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-yellow-800 font-semibold mb-2">🧪 Debug Test</h3>
                <button onclick="testClick()" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">
                    Test Click
                </button>
                <button onclick="alert('Direct alert works!')" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Direct Alert
                </button>
                <small class="block mt-2 text-yellow-600">Jika button ini tidak berfungsi, ada masalah dengan JavaScript</small>
            </div>

            <!-- Bulk Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <div class="text-green-600 text-4xl mb-4">✅</div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Bulk Approve</h3>
                    <p class="text-green-600 mb-4">Setujui semua surat cuti pending sekaligus</p>
                    <button id="bulkApproveBtn" onclick="simpleBulkApprove()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 cursor-pointer" style="cursor: pointer;">
                        🟢 Approve Semua
                    </button>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <div class="text-red-600 text-4xl mb-4">❌</div>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Bulk Reject</h3>
                    <p class="text-red-600 mb-4">Tolak semua surat cuti pending sekaligus</p>
                    <button id="bulkRejectBtn" onclick="simpleBulkReject()" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 cursor-pointer" style="cursor: pointer;">
                        🔴 Reject Semua
                    </button>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-800">{{ $pendingSuratCuti->count() }}</div>
                    <div class="text-yellow-600 font-medium">Surat Pending</div>
                </div>

                <div class="bg-green-100 border border-green-300 rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-green-800">{{ $approvedSuratCuti->count() }}</div>
                    <div class="text-green-600 font-medium">Surat Disetujui (10 terbaru)</div>
                </div>

                <div class="bg-blue-100 border border-blue-300 rounded-lg p-6 text-center">
                    <div class="text-lg font-bold text-blue-800">{{ auth()->user()->nama }}</div>
                    <div class="text-blue-600 font-medium">Logged in as {{ auth()->user()->role }}</div>
                </div>
            </div>

            <!-- Pending Surat Cuti -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-yellow-800">⏳ Surat Cuti Pending ({{ $pendingSuratCuti->count() }})</h2>
                </div>
                <div class="p-6">
                    @if($pendingSuratCuti->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingSuratCuti as $surat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $surat->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $surat->pengaju->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->pengaju->unit_kerja }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->jenisCuti->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->tanggal_awal->format('d/m/Y') }} - {{ $surat->tanggal_akhir->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->jumlah_hari }} hari</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $surat->status }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('surat-cuti.show', $surat) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('surat-cuti.pdf', $surat) }}" class="text-red-600 hover:text-red-900" target="_blank">PDF</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="text-blue-400 mr-3">ℹ️</div>
                                <div class="text-blue-700">Tidak ada surat cuti pending</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recently Approved Surat Cuti -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="bg-green-50 border-b border-green-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-green-800">✅ Surat Cuti Disetujui (10 Terbaru)</h2>
                </div>
                <div class="p-6">
                    @if($approvedSuratCuti->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($approvedSuratCuti as $surat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $surat->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $surat->pengaju->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->pengaju->unit_kerja }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->jenisCuti->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->tanggal_awal->format('d/m/Y') }} - {{ $surat->tanggal_akhir->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->jumlah_hari }} hari</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('surat-cuti.show', $surat) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('surat-cuti.pdf', $surat) }}" class="text-red-600 hover:text-red-900" target="_blank">PDF</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="text-blue-400 mr-3">ℹ️</div>
                                <div class="text-blue-700">Belum ada surat cuti yang disetujui</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-white border border-blue-200 rounded-lg shadow-sm">
                <div class="bg-blue-50 border-b border-blue-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-blue-800">🔧 Debug Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Fitur Bulk Operations:</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✅</span>
                                    <span>Bulk Approve: Setujui semua surat pending + buat disposisi otomatis</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">❌</span>
                                    <span>Bulk Reject: Tolak semua surat pending</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-blue-500 mr-2">🔄</span>
                                    <span>Auto-update sisa cuti saat approve</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-purple-500 mr-2">📄</span>
                                    <span>PDF langsung bisa digenerate</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Workflow:</h3>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                                    <span>User submit surat cuti (status: draft/proses)</span>
                                </li>
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                                    <span>Admin klik "Approve Semua"</span>
                                </li>
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                                    <span>Sistem auto-create disposisi</span>
                                </li>
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                                    <span>Status berubah ke "disetujui"</span>
                                </li>
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                                    <span>Sisa cuti auto-update</span>
                                </li>
                                <li class="flex">
                                    <span class="bg-gray-200 text-gray-700 rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">6</span>
                                    <span>PDF siap digenerate dengan data benar</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Processing...</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Sedang memproses bulk operation...</p>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Inline JavaScript - Load immediately -->
<script>
console.log('🚀 Inline Script Loading...');

// Define functions immediately in global scope
window.simpleBulkApprove = function() {
    console.log('🟢 Simple Bulk Approve Called!');

    if (confirm('Yakin ingin menyetujui SEMUA surat cuti pending?\n\nIni akan:\n1. Mengubah status ke "disetujui"\n2. Membuat disposisi otomatis\n3. Update sisa cuti otomatis\n\nLanjutkan?')) {

        // Show simple loading
        const btn = document.getElementById('bulkApproveBtn');
        if (btn) {
            btn.innerHTML = '⏳ Processing...';
            btn.disabled = true;
            btn.style.opacity = '0.6';
        }

        // Simple form submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.surat-cuti.bulk-approve-all") }}';
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        form.appendChild(csrfInput);
        document.body.appendChild(form);

        console.log('📤 Submitting form to:', form.action);
        form.submit();
    }
};

window.simpleBulkReject = function() {
    console.log('🔴 Simple Bulk Reject Called!');

    if (confirm('Yakin ingin menolak SEMUA surat cuti pending?\n\nIni tidak bisa dibatalkan!')) {

        // Show simple loading
        const btn = document.getElementById('bulkRejectBtn');
        if (btn) {
            btn.innerHTML = '⏳ Processing...';
            btn.disabled = true;
            btn.style.opacity = '0.6';
        }

        // Simple form submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.surat-cuti.bulk-reject-all") }}';
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        form.appendChild(csrfInput);
        document.body.appendChild(form);

        console.log('📤 Submitting form to:', form.action);
        form.submit();
    }
};

// Test function
window.testClick = function() {
    alert('✅ Button click works!');
    console.log('✅ Test click successful');
};

// Verify functions are defined
console.log('🔍 Function check:', {
    simpleBulkApprove: typeof window.simpleBulkApprove,
    simpleBulkReject: typeof window.simpleBulkReject,
    testClick: typeof window.testClick
});

console.log('✅ Inline script loaded successfully!');
</script>
