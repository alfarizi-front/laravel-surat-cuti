<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Cap & Tanda Tangan - {{ $user->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pdf-preview {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-height: 400px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="no-print mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    Preview Cap & Tanda Tangan
                </h1>
                <div class="space-x-2">
                    <button onclick="window.print()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                    <button onclick="window.close()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="no-print bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $user->nama }}</h3>
                    <p class="text-gray-600">{{ $user->jabatan }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $user->role }} • {{ $user->jenis_pegawai }}
                        @if($user->nip)
                            • NIP: {{ $user->nip }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- PDF Preview -->
        <div class="pdf-preview rounded-lg p-8 mx-auto max-w-2xl">
            
            <!-- Document Header -->
            <div class="text-center mb-8">
                <h2 class="text-lg font-bold">DINAS KESEHATAN KABUPATEN PURWOREJO</h2>
                <p class="text-sm">Jl. Tentara Pelajar No. 5 Purworejo</p>
                <hr class="my-4 border-gray-400">
            </div>

            <!-- Document Content Sample -->
            <div class="mb-8">
                <h3 class="text-center font-bold mb-4">SURAT KEPUTUSAN</h3>
                <p class="text-sm mb-4">
                    Berdasarkan pertimbangan dan ketentuan yang berlaku, dengan ini kami menyatakan...
                </p>
                <p class="text-sm mb-8">
                    [Isi dokumen contoh untuk preview tampilan cap dan tanda tangan]
                </p>
            </div>

            <!-- Signature Section -->
            <div class="flex justify-end">
                <div class="text-center">
                    <p class="text-sm mb-2">Purworejo, {{ now()->format('d F Y') }}</p>
                    <p class="text-sm mb-4">Kepala Dinas Kesehatan Daerah<br>Kabupaten Purworejo</p>
                    
                    <!-- Cap & Signature Preview -->
                    <div class="min-h-32 flex flex-col items-center justify-center space-y-2">
                        @if($user->cap_stempel && $user->gunakan_cap)
                            <div>
                                <img src="{{ asset('storage/' . $user->cap_stempel) }}" 
                                     alt="Cap Stempel" 
                                     class="max-h-16 object-contain">
                            </div>
                        @endif
                        
                        @if($user->tanda_tangan)
                            <div>
                                <img src="{{ asset('storage/' . $user->tanda_tangan) }}" 
                                     alt="Tanda Tangan" 
                                     class="max-h-16 object-contain">
                            </div>
                        @else
                            <div class="text-gray-400 text-xs italic py-4">
                                (Tanda tangan digital)
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 text-sm">
                        <p class="font-semibold">{{ $user->nama }}</p>
                        @if($user->nip)
                            <p class="text-xs">NIP. {{ $user->nip }}</p>
                        @else
                            <p class="text-xs">{{ $user->jabatan }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Info -->
        <div class="no-print mt-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold mb-3">Status Preview:</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center">
                    @if($user->tanda_tangan)
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Tanda tangan: <strong>Ada</strong></span>
                    @else
                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                        <span>Tanda tangan: <strong>Tidak ada</strong></span>
                    @endif
                </div>
                
                <div class="flex items-center">
                    @if($user->cap_stempel)
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Cap stempel: <strong>Ada</strong></span>
                    @else
                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                        <span>Cap stempel: <strong>Tidak ada</strong></span>
                    @endif
                </div>
                
                <div class="flex items-center">
                    @if($user->gunakan_cap)
                        <i class="fas fa-toggle-on text-green-500 mr-2"></i>
                        <span>Status cap: <strong>Aktif</strong></span>
                    @else
                        <i class="fas fa-toggle-off text-gray-500 mr-2"></i>
                        <span>Status cap: <strong>Nonaktif</strong></span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Catatan:</strong> Preview ini menunjukkan bagaimana cap dan tanda tangan akan tampil pada surat PDF resmi.
                    @if(!$user->cap_stempel || !$user->gunakan_cap)
                        Jika cap stempel tidak aktif, hanya tanda tangan yang akan ditampilkan.
                    @endif
                </p>
            </div>
        </div>
    </div>
</body>
</html>
