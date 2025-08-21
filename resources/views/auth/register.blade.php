<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - Sistem Surat Cuti</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-inter antialiased">
    <!-- Animated Background -->
    <div class="min-h-screen gradient-bg relative overflow-hidden">
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-bounce-gentle"></div>
        <div class="absolute top-1/4 right-20 w-16 h-16 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white bg-opacity-10 rounded-full animate-bounce-gentle" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/4 right-1/3 w-8 h-8 bg-white bg-opacity-10 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>
        
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <!-- Main Register Container -->
            <div class="w-full max-w-2xl animate-slide-up">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4 animate-bounce-gentle">
                        <i class="fas fa-user-plus text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Daftar Akun Baru</h1>
                    <p class="text-white text-opacity-80">Sistem Surat Cuti - Dinas Kesehatan</p>
                    <div class="w-20 h-1 bg-white bg-opacity-30 rounded-full mx-auto mt-3"></div>
                </div>

                <!-- Register Form -->
                <div class="glass-effect rounded-2xl p-8 shadow-2xl">
                    <!-- Session Status -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 bg-opacity-20 border border-red-300 border-opacity-30 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-circle text-red-300 mr-2"></i>
                                <span class="text-sm text-white font-medium">Terjadi kesalahan:</span>
                            </div>
                            <ul class="text-sm text-red-200 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div class="space-y-2">
                                <label for="nama" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-user mr-2"></i>Nama Lengkap
                                </label>
                                <input id="nama" 
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300" 
                                       type="text" 
                                       name="nama" 
                                       value="{{ old('nama') }}" 
                                       placeholder="Masukkan nama lengkap"
                                       required autofocus />
                            </div>

                            <!-- NIP -->
                            <div class="space-y-2">
                                <label for="nip" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-id-card mr-2"></i>NIP
                                </label>
                                <input id="nip" 
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300" 
                                       type="text" 
                                       name="nip" 
                                       value="{{ old('nip') }}" 
                                       placeholder="Masukkan Nomor Induk Pegawai (18 digit)"
                                       required 
                                       pattern="[0-9]{18}"
                                       title="NIP harus berupa 18 digit angka"
                                       maxlength="18"
                                       minlength="18"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Unit Kerja -->
                            <div class="space-y-2">
                                <label for="unit_kerja" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-building mr-2"></i>Unit Kerja
                                </label>
                                <select id="unit_kerja" 
                                        name="unit_kerja" 
                                        class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300"
                                        required onchange="updateJabatanOptions()">
                                    <option value="" class="text-gray-800">Pilih Unit Kerja</option>
                                    @foreach($allUnitKerja as $key => $value)
                                        <option value="{{ $key }}" class="text-gray-800" {{ old('unit_kerja') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jabatan -->
                            <div class="space-y-2">
                                <label for="jabatan" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-briefcase mr-2"></i>Jabatan
                                </label>
                                <select id="jabatan" 
                                        name="jabatan" 
                                        class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300"
                                        required>
                                    <option value="" class="text-gray-800">Pilih Jabatan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Jenis Pegawai -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-white text-opacity-90">
                                <i class="fas fa-user-tag mr-2"></i>Jenis Pegawai
                            </label>
                            <div class="flex space-x-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="jenis_pegawai" value="ASN" class="w-4 h-4 text-white bg-white bg-opacity-20 border-white border-opacity-30 focus:ring-white focus:ring-opacity-50" {{ old('jenis_pegawai') == 'ASN' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-sm text-white text-opacity-90">ASN</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="jenis_pegawai" value="PPPK" class="w-4 h-4 text-white bg-white bg-opacity-20 border-white border-opacity-30 focus:ring-white focus:ring-opacity-50" {{ old('jenis_pegawai') == 'PPPK' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-sm text-white text-opacity-90">PPPK</span>
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-white text-opacity-90">
                                <i class="fas fa-envelope mr-2"></i>Email Address
                            </label>
                            <input id="email" 
                                   class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="nama@dinkes.go.id"
                                   required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-lock mr-2"></i>Password
                                </label>
                                <input id="password" 
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300" 
                                       type="password" 
                                       name="password" 
                                       placeholder="Minimal 8 karakter"
                                       required />
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-medium text-white text-opacity-90">
                                    <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                                </label>
                                <input id="password_confirmation" 
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300" 
                                       type="password" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password"
                                       required />
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 flex items-center justify-center space-x-2">
                            <i class="fas fa-user-plus"></i>
                            <span>Daftar Sekarang</span>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-white text-opacity-80 text-sm">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-white font-medium hover:underline transition-all duration-200">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data jabatan berdasarkan unit kerja
        const jabatanOptions = @json($jabatanOptions);

        function updateJabatanOptions() {
            const unitKerja = document.getElementById('unit_kerja').value;
            const jabatanSelect = document.getElementById('jabatan');

            // Clear existing options
            jabatanSelect.innerHTML = '<option value="" class="text-gray-800">Pilih Jabatan</option>';

            let options = [];

            // Determine which jabatan options to show
            if (unitKerja.includes('Puskesmas')) {
                options = jabatanOptions.puskesmas || {};
            } else if (unitKerja === 'Sekretariat') {
                options = jabatanOptions.sekretariat || {};
            } else if (unitKerja.includes('Bidang')) {
                options = jabatanOptions.bidang || {};
            }

            // Add options
            Object.entries(options).forEach(([key, value]) => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = value;
                option.className = 'text-gray-800';
                jabatanSelect.appendChild(option);
            });
        }

        // Initialize jabatan options on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateJabatanOptions();
        });
    </script>
</body>
</html>
