<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Sistem Surat Cuti</title>

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
            <!-- Main Login Container -->
            <div class="w-full max-w-md animate-slide-up">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4 animate-bounce-gentle">
                        <i class="fas fa-file-medical text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Sistem Surat Cuti</h1>
                    <p class="text-white text-opacity-80">Dinas Kesehatan Kota</p>
                    <div class="w-20 h-1 bg-white bg-opacity-30 rounded-full mx-auto mt-3"></div>
                </div>

                <!-- Login Form -->
                <div class="glass-effect rounded-2xl p-8 shadow-2xl">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-100 bg-opacity-20 border border-green-300 border-opacity-30 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-300 mr-2"></i>
                                <span class="text-sm text-white">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-white text-opacity-90">
                                <i class="fas fa-envelope mr-2"></i>Email Address
                            </label>
                            <div class="relative">
                                <input id="email"
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       required autofocus autocomplete="username" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-user text-white text-opacity-40"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-white text-opacity-90">
                                <i class="fas fa-lock mr-2"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password"
                                       class="input-focus block w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-60 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-300"
                                       type="password"
                                       name="password"
                                       placeholder="Enter your password"
                                       required autocomplete="current-password" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" onclick="togglePassword()" class="text-white text-opacity-40 hover:text-opacity-60 transition-colors">
                                        <i id="password-icon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="w-4 h-4 text-white bg-white bg-opacity-20 border-white border-opacity-30 rounded focus:ring-white focus:ring-opacity-50 focus:ring-2" name="remember">
                                <span class="ml-2 text-sm text-white text-opacity-90">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-white text-opacity-80 hover:text-opacity-100 transition-all duration-200 hover:underline" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 flex items-center justify-center space-x-2">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In</span>
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-white text-opacity-80 text-sm">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-white font-medium hover:underline transition-all duration-200 inline-flex items-center">
                                <i class="fas fa-user-plus mr-1"></i>
                                Daftar di sini
                            </a>
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white border-opacity-30"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-transparent text-white text-opacity-70">Quick Login (Debug)</span>
                        </div>
                    </div>

                    <!-- Quick Login Buttons -->
                    <div class="space-y-3">
                        <div class="text-center">
                            <p class="text-xs text-white text-opacity-70 mb-3">🚀 Development Mode - Quick Access</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <!-- Admin & Management -->
                            <button onclick="quickLogin('admin@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700">
                                <i class="fas fa-crown"></i>
                                <span>Admin</span>
                            </button>
                            <button onclick="quickLogin('kadin@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700">
                                <i class="fas fa-building"></i>
                                <span>KADIN</span>
                            </button>

                            <!-- Karyawan -->
                            <button onclick="quickLogin('asn@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700">
                                <i class="fas fa-user-tie"></i>
                                <span>ASN</span>
                            </button>
                            <button onclick="quickLogin('pppk@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-user"></i>
                                <span>PPPK</span>
                            </button>

                            <!-- Disposisi Users -->
                            <button onclick="quickLogin('katu@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Ka TU</span>
                            </button>
                            <button onclick="quickLogin('kapus@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                                <i class="fas fa-hospital"></i>
                                <span>Ka Puskesmas</span>
                            </button>

                            <button onclick="quickLogin('umpeg@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700">
                                <i class="fas fa-chart-bar"></i>
                                <span>Umpeg</span>
                            </button>
                            <button onclick="quickLogin('sekdin@dinkes.go.id')" class="quick-login-btn bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700">
                                <i class="fas fa-file-signature"></i>
                                <span>Sekdin</span>
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <p class="text-xs text-white text-opacity-60">
                                Password: <code class="bg-white bg-opacity-20 px-2 py-1 rounded">password</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .quick-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: white;
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            transform: scale(1);
        }
        .quick-login-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        function quickLogin(email) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberInput = document.getElementById('remember_me');

            // Add loading animation
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';
            button.disabled = true;

            setTimeout(() => {
                emailInput.value = email;
                passwordInput.value = 'password';
                rememberInput.checked = true;

                // Add focus effect
                emailInput.classList.add('ring-2', 'ring-white', 'ring-opacity-50');
                passwordInput.classList.add('ring-2', 'ring-white', 'ring-opacity-50');

                // Reset button
                button.innerHTML = originalContent;
                button.disabled = false;

                // Focus on login button
                document.querySelector('button[type="submit"]').focus();
            }, 500);
        }

        // Add floating animation to background elements
        document.addEventListener('DOMContentLoaded', function() {
            const floatingElements = document.querySelectorAll('.absolute');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.5}s`;
            });
        });
    </script>
        </div>
    </div>
</body>
</html>
