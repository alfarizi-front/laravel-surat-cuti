<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-file-pdf text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        Preview Surat Cuti
                    </h2>
                    <p class="text-sm text-gray-600">Surat Cuti ID: {{ $suratCuti->id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('surat-cuti.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button onclick="window.print()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                    <div class="mt-2 text-sm">
                        <p>Untuk mengaktifkan download PDF, jalankan command berikut:</p>
                        <code class="bg-gray-800 text-green-400 px-2 py-1 rounded">composer require barryvdh/laravel-dompdf</code>
                    </div>
                </div>
            @endif

            <!-- PDF Content -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden print:shadow-none print:rounded-none">
                <div class="p-8 print:p-0">
                    <!-- Include PDF content -->
                    @include('surat-cuti.pdf-content', [
                        'suratCuti' => $suratCuti,
                        'disposisiList' => $disposisiList ?? collect(),
                        'kadinDisposisi' => $kadinDisposisi ?? null,
                        'pengaju' => $pengaju ?? $suratCuti->pengaju,
                        'jenisCuti' => $jenisCuti ?? $suratCuti->jenisCuti,
                        'jumlah_hari' => $jumlah_hari ?? $suratCuti->jumlah_hari,
                        'isPreview' => true,
                        'signatureHelper' => $signatureHelper ?? null,
                        'formatTanggal' => $formatTanggal ?? null
                    ])
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-center space-x-4 print:hidden">
                <button onclick="window.print()" 
                        class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-print mr-2"></i>
                    Print Surat
                </button>
                <a href="{{ route('surat-cuti.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print\:shadow-none, .print\:shadow-none * {
                visibility: visible;
            }
            .print\:shadow-none {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none !important;
            }
            .print\:p-0 {
                padding: 0 !important;
            }
            .print\:rounded-none {
                border-radius: 0 !important;
            }
        }
    </style>
</x-app-layout>
