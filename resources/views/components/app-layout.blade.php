<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Surat Cuti') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-10px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="font-inter antialiased">
    <div class="min-h-screen gradient-bg">
        @auth
            @include('layouts.navigation')
        @endauth

        <!-- Page Heading -->
        @isset($header)
            <header class="glass-card shadow-lg border-b border-white border-opacity-20 animate-fade-in">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-1 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full"></div>
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="animate-fade-in">
            <!-- Success Alert -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 animate-slide-in">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 transform hover:scale-105 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-green-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if (session('error'))
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 animate-slide-in">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 transform hover:scale-105 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-red-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Info Alert -->
            @if (session('info'))
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 animate-slide-in">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 transform hover:scale-105 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('info') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-blue-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
