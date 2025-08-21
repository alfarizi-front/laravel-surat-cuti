 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Accounts - Sistem Surat Cuti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Test Accounts - Sistem Surat Cuti</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sekretariat Workflow -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-blue-800 mb-4">🏢 Sekretariat Workflow</h2>
                        <p class="text-sm text-blue-700 mb-4">Alur: Karyawan → Kasubag → Umpeg/Perencanaan Keu (salah satu) → Sekdin → KADIN</p>
                        
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">1. Karyawan Sekretariat</h3>
                                <p class="text-sm text-gray-600">Email: karyawan.sekretariat@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs">ASN</span>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">2. Kasubag Sekretariat</h3>
                                <p class="text-sm text-gray-600">Email: kasubag.sekretariat@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Paraf</span>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">3a. Kasubag Umpeg</h3>
                                <p class="text-sm text-gray-600">Email: kasubag.umpeg@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">Conditional</span>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">3b. Kasubag Perencanaan Keu</h3>
                                <p class="text-sm text-gray-600">Email: kasubag.perencanaan@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">Conditional</span>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">4. Sekretaris Dinas</h3>
                                <p class="text-sm text-gray-600">Email: sekretaris.dinas@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Paraf</span>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">5. Kepala Dinas</h3>
                                <p class="text-sm text-gray-600">Email: kepala.dinas@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Tanda Tangan</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Existing Accounts -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">👥 Existing Test Accounts</h2>
                        
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">Admin</h3>
                                <p class="text-sm text-gray-600">Email: admin@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">ASN Karyawan</h3>
                                <p class="text-sm text-gray-600">Email: asn@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">PPPK Karyawan</h3>
                                <p class="text-sm text-gray-600">Email: pppk@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                            </div>
                            
                            <div class="bg-white p-3 rounded border">
                                <h3 class="font-medium text-gray-800">Test PPPK Sekretariat</h3>
                                <p class="text-sm text-gray-600">Email: test.pppk.sekretariat@dinkes.go.id</p>
                                <p class="text-sm text-gray-600">Password: password</p>
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs">PPPK</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Features Info -->
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-yellow-800 mb-3">🚀 New Features Implemented</h2>
                    <ul class="list-disc list-inside text-yellow-700 space-y-1">
                        <li><strong>Flexible Disposisi Processing:</strong> Any disposisi can be processed regardless of order</li>
                        <li><strong>Conditional Approval for Sekretariat:</strong> If Umpeg OR Perencanaan Keu approves, the other is auto-approved</li>
                        <li><strong>Riwayat Disposisi:</strong> All users can view their disposisi history</li>
                        <li><strong>Enhanced Status Tracking:</strong> Real-time progress tracking for all disposisi</li>
                        <li><strong>PPPK Disposisi History:</strong> PPPK users now have disposisi history in PDF</li>
                    </ul>
                </div>
                
                <!-- Testing Instructions -->
                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-green-800 mb-3">🧪 Testing Instructions</h2>
                    <ol class="list-decimal list-inside text-green-700 space-y-2">
                        <li>Login as <strong>Karyawan Sekretariat</strong> and create a surat cuti</li>
                        <li>Submit the surat cuti to start the disposisi process</li>
                        <li>Login as any of the disposisi users (Kasubag, Umpeg, Perencanaan Keu, Sekdin, KADIN)</li>
                        <li>Process disposisi in any order - no need to wait for others</li>
                        <li>Test the conditional logic: approve as either Umpeg OR Perencanaan Keu</li>
                        <li>Check that the other one is automatically approved</li>
                        <li>View "Riwayat Disposisi" to see the complete history</li>
                    </ol>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                        Go to Login Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>