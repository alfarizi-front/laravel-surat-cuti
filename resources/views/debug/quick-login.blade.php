<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Login - Sistem Surat Cuti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-card {
            transition: all 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-rocket text-blue-500 mr-3"></i>
                Quick Login Dashboard
            </h1>
            <p class="text-gray-600">Sistem Surat Cuti - Development Mode</p>
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-300 rounded-lg">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                <span class="text-yellow-800 text-sm font-medium">Development Environment Only</span>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $totalAccounts }}</div>
                <div class="text-sm text-gray-600">Total Accounts</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $workflows }}</div>
                <div class="text-sm text-gray-600">Workflows</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $roles }}</div>
                <div class="text-sm text-gray-600">Different Roles</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $features }}</div>
                <div class="text-sm text-gray-600">New Features</div>
            </div>
        </div>

        <!-- Quick Access Buttons -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Sekretariat Workflow -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-building mr-3"></i>
                        Sekretariat Workflow
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Conditional Umpeg/Perencanaan Keu Logic</p>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Karyawan Sekretariat -->
                    <a href="/quick-login/karyawan-sekretariat" class="login-card block bg-gray-50 hover:bg-blue-50 rounded-lg p-4 border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Karyawan Sekretariat</h3>
                                <p class="text-sm text-gray-600">Staff Sekretariat</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-green-100 text-green-800">ASN</span>
                                <div class="text-xs text-gray-500 mt-1">Step 1: Pengaju</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kasubag Sekretariat -->
                    <a href="/quick-login/kasubag-sekretariat" class="login-card block bg-gray-50 hover:bg-yellow-50 rounded-lg p-4 border-2 border-transparent hover:border-yellow-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kasubag Sekretariat</h3>
                                <p class="text-sm text-gray-600">Kepala Sub Bagian</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-yellow-100 text-yellow-800">Paraf</span>
                                <div class="text-xs text-gray-500 mt-1">Step 2: Kasubag</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kasubag Umpeg -->
                    <a href="/quick-login/kasubag-umpeg" class="login-card block bg-gray-50 hover:bg-purple-50 rounded-lg p-4 border-2 border-transparent hover:border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kasubag Umpeg</h3>
                                <p class="text-sm text-gray-600">Umum & Kepegawaian</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-purple-100 text-purple-800">Conditional</span>
                                <div class="text-xs text-gray-500 mt-1">Step 3a: OR Logic</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kasubag Perencanaan -->
                    <a href="/quick-login/kasubag-perencanaan" class="login-card block bg-gray-50 hover:bg-purple-50 rounded-lg p-4 border-2 border-transparent hover:border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kasubag Perencanaan</h3>
                                <p class="text-sm text-gray-600">Perencanaan & Keuangan</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-purple-100 text-purple-800">Conditional</span>
                                <div class="text-xs text-gray-500 mt-1">Step 3b: OR Logic</div>
                            </div>
                        </div>
                    </a>

                    <!-- Sekretaris Dinas -->
                    <a href="/quick-login/sekretaris-dinas" class="login-card block bg-gray-50 hover:bg-orange-50 rounded-lg p-4 border-2 border-transparent hover:border-orange-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Sekretaris Dinas</h3>
                                <p class="text-sm text-gray-600">Sekretaris Dinas Kesehatan</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-orange-100 text-orange-800">Paraf</span>
                                <div class="text-xs text-gray-500 mt-1">Step 4: Sekdin</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kepala Dinas -->
                    <a href="/quick-login/kepala-dinas" class="login-card block bg-gray-50 hover:bg-red-50 rounded-lg p-4 border-2 border-transparent hover:border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kepala Dinas</h3>
                                <p class="text-sm text-gray-600">KADIN Kesehatan</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-red-100 text-red-800">TTD Final</span>
                                <div class="text-xs text-gray-500 mt-1">Step 5: Final</div>
                            </div>
                        </div>
                    </a>

                    <!-- Test PPPK -->
                    <a href="/quick-login/test-pppk-sekretariat" class="login-card block bg-gray-50 hover:bg-indigo-50 rounded-lg p-4 border-2 border-transparent hover:border-indigo-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Test PPPK Sekretariat</h3>
                                <p class="text-sm text-gray-600">Staff PPPK</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-indigo-100 text-indigo-800">PPPK</span>
                                <div class="text-xs text-gray-500 mt-1">Test Account</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Puskesmas & Other Workflows -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-hospital mr-3"></i>
                        Puskesmas & Others
                    </h2>
                    <p class="text-green-100 text-sm mt-1">Existing Workflows</p>
                </div>
                <div class="p-6 space-y-3">
                    <!-- ASN -->
                    <a href="/quick-login/asn" class="login-card block bg-gray-50 hover:bg-green-50 rounded-lg p-4 border-2 border-transparent hover:border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Karyawan ASN</h3>
                                <p class="text-sm text-gray-600">Aparatur Sipil Negara</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-green-100 text-green-800">ASN</span>
                                <div class="text-xs text-gray-500 mt-1">General</div>
                            </div>
                        </div>
                    </a>

                    <!-- PPPK -->
                    <a href="/quick-login/pppk" class="login-card block bg-gray-50 hover:bg-blue-50 rounded-lg p-4 border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Karyawan PPPK</h3>
                                <p class="text-sm text-gray-600">Pegawai Perjanjian Kerja</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-blue-100 text-blue-800">PPPK</span>
                                <div class="text-xs text-gray-500 mt-1">General</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kepala Tata Usaha -->
                    <a href="/quick-login/katu" class="login-card block bg-gray-50 hover:bg-yellow-50 rounded-lg p-4 border-2 border-transparent hover:border-yellow-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kepala Tata Usaha</h3>
                                <p class="text-sm text-gray-600">KTU Puskesmas</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-yellow-100 text-yellow-800">Paraf</span>
                                <div class="text-xs text-gray-500 mt-1">Puskesmas</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kepala Puskesmas -->
                    <a href="/quick-login/kapus" class="login-card block bg-gray-50 hover:bg-purple-50 rounded-lg p-4 border-2 border-transparent hover:border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kepala Puskesmas</h3>
                                <p class="text-sm text-gray-600">Kapus</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-purple-100 text-purple-800">TTD</span>
                                <div class="text-xs text-gray-500 mt-1">Puskesmas</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kasubag Umpeg -->
                    <a href="/quick-login/umpeg" class="login-card block bg-gray-50 hover:bg-orange-50 rounded-lg p-4 border-2 border-transparent hover:border-orange-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kasubag Umpeg</h3>
                                <p class="text-sm text-gray-600">Umum & Kepegawaian</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-orange-100 text-orange-800">Paraf</span>
                                <div class="text-xs text-gray-500 mt-1">Cross-Unit</div>
                            </div>
                        </div>
                    </a>

                    <!-- Sekretaris Dinas (Original) -->
                    <a href="/quick-login/sekdin" class="login-card block bg-gray-50 hover:bg-red-50 rounded-lg p-4 border-2 border-transparent hover:border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Sekdin (Original)</h3>
                                <p class="text-sm text-gray-600">Sekretaris Dinas</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-red-100 text-red-800">Paraf</span>
                                <div class="text-xs text-gray-500 mt-1">Legacy</div>
                            </div>
                        </div>
                    </a>

                    <!-- Kepala Bidang -->
                    <a href="/quick-login/kabid" class="login-card block bg-gray-50 hover:bg-indigo-50 rounded-lg p-4 border-2 border-transparent hover:border-indigo-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kepala Bidang</h3>
                                <p class="text-sm text-gray-600">Kabid</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-indigo-100 text-indigo-800">TTD</span>
                                <div class="text-xs text-gray-500 mt-1">Bidang</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Admin & System -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-cog mr-3"></i>
                        Admin & System
                    </h2>
                    <p class="text-purple-100 text-sm mt-1">Management Accounts</p>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Admin -->
                    <a href="/quick-login/admin" class="login-card block bg-gray-50 hover:bg-purple-50 rounded-lg p-4 border-2 border-transparent hover:border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">System Admin</h3>
                                <p class="text-sm text-gray-600">Full System Access</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-purple-100 text-purple-800">ADMIN</span>
                                <div class="text-xs text-gray-500 mt-1">All Access</div>
                            </div>
                        </div>
                    </a>

                    <!-- KADIN -->
                    <a href="/quick-login/kadin" class="login-card block bg-gray-50 hover:bg-red-50 rounded-lg p-4 border-2 border-transparent hover:border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">KADIN (Original)</h3>
                                <p class="text-sm text-gray-600">Kepala Dinas</p>
                            </div>
                            <div class="text-right">
                                <span class="role-badge bg-red-100 text-red-800">KADIN</span>
                                <div class="text-xs text-gray-500 mt-1">Legacy</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Quick Actions -->
                <div class="border-t bg-gray-50 px-6 py-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="/debug/test-accounts" class="block text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>View Detailed Account Info
                        </a>
                        <a href="/dashboard" class="block text-sm text-green-600 hover:text-green-800">
                            <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                        </a>
                        <a href="/login" class="block text-sm text-gray-600 hover:text-gray-800">
                            <i class="fas fa-sign-in-alt mr-2"></i>Regular Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Info -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    New Features & Testing Guide
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">🚀 New Features</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span><strong>Flexible Disposisi:</strong> Process in any order</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span><strong>Conditional Logic:</strong> Umpeg OR Perencanaan Keu</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span><strong>Riwayat Disposisi:</strong> Complete history tracking</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span><strong>PPPK Support:</strong> Enhanced PDF with history</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">🧪 Testing Scenarios</h3>
                        <ol class="space-y-2 text-sm text-gray-600 list-decimal list-inside">
                            <li>Login as <strong>Karyawan Sekretariat</strong> → Create surat cuti</li>
                            <li>Login as <strong>Kasubag Umpeg</strong> → Approve (auto-approves Perencanaan)</li>
                            <li>Login as <strong>Sekretaris Dinas</strong> → Approve</li>
                            <li>Login as <strong>Kepala Dinas</strong> → Final approval</li>
                            <li>Check <strong>Riwayat Disposisi</strong> for complete history</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>Development Environment - Quick Login Dashboard</p>
            <p class="mt-1">All accounts use password: <code class="bg-gray-200 px-2 py-1 rounded">password</code></p>
        </div>
    </div>

    <script>
        // Add click animation
        document.querySelectorAll('.login-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Add loading state
                const originalContent = this.innerHTML;
                this.innerHTML = '<div class="flex items-center justify-center py-4"><i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>Logging in...</div>';
                
                // Reset after a short delay if navigation fails
                setTimeout(() => {
                    this.innerHTML = originalContent;
                }, 3000);
            });
        });
    </script>
</body>
</html>