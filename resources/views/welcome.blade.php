<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Cuti - Dinas Kesehatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <!-- Logo -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
                    <i class="fas fa-file-medical text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistem Surat Cuti</h1>
                <p class="text-gray-600">Dinas Kesehatan Kota</p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                @if(app()->environment('local'))
                    <!-- Development Mode - Quick Login -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-code text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800 font-medium">Development Mode</span>
                        </div>
                        <p class="text-yellow-700 text-sm mb-4">Quick access to all test accounts</p>
                        
                        <a href="{{ route('debug.quick-login') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-rocket mr-2"></i>
                            Quick Login Dashboard
                        </a>
                    </div>
                @endif

                <!-- Regular Login -->
                <a href="{{ route('login') }}" class="block w-full bg-white text-gray-800 font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border border-gray-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login Regular
                </a>

                <!-- Register -->
                <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Akun Baru
                </a>
            </div>

            @if(app()->environment('local'))
                <!-- Quick Access Cards -->
                <div class="mt-8 grid grid-cols-2 gap-3">
                    <a href="/quick-login/admin" class="bg-red-500 text-white p-3 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                        <i class="fas fa-crown mb-1"></i><br>Admin
                    </a>
                    <a href="/quick-login/karyawan-sekretariat" class="bg-blue-500 text-white p-3 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                        <i class="fas fa-user mb-1"></i><br>Karyawan
                    </a>
                    <a href="/quick-login/kasubag-umpeg" class="bg-purple-500 text-white p-3 rounded-lg text-sm font-medium hover:bg-purple-600 transition-colors">
                        <i class="fas fa-tasks mb-1"></i><br>Umpeg
                    </a>
                    <a href="/quick-login/kepala-dinas" class="bg-green-500 text-white p-3 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                        <i class="fas fa-building mb-1"></i><br>KADIN
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p>Password semua akun: <code class="bg-gray-200 px-2 py-1 rounded">password</code></p>
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} Dinas Kesehatan Kota</p>
                @if(app()->environment('local'))
                    <p class="mt-1">Environment: <span class="font-mono bg-gray-200 px-2 py-1 rounded">{{ app()->environment() }}</span></p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>