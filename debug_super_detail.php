<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    SUPER DETAILED DEBUG - STEP BY STEP                     ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

// List all users to choose from
echo "1. Available Users:\n";
$users = \App\Models\User::where('role', '!=', 'admin')->get();
foreach ($users as $index => $user) {
    echo "   " . ($index + 1) . ". {$user->nama} - {$user->unit_kerja} - {$user->jenis_pegawai}\n";
}

// Let's test with the first ASN user
$testUser = \App\Models\User::where('jenis_pegawai', 'ASN')->first();
if (!$testUser) {
    $testUser = $users->first();
}

echo "\n2. Testing with: {$testUser->nama}\n";
echo "   ID: {$testUser->id}\n";
echo "   Unit Kerja: {$testUser->unit_kerja}\n";
echo "   Jenis Pegawai: {$testUser->jenis_pegawai}\n";
echo "   Role: {$testUser->role}\n\n";

// Check what template will be selected
$controller = new \App\Http\Controllers\SuratCutiController();
$reflection = new ReflectionClass($controller);

$selectTemplateMethod = $reflection->getMethod('selectPDFTemplate');
$selectTemplateMethod->setAccessible(true);
$selectedTemplate = $selectTemplateMethod->invoke($controller, $testUser->unit_kerja);

echo "3. Template Selection:\n";
echo "   Unit Kerja: '{$testUser->unit_kerja}'\n";
echo "   Selected Template: {$selectedTemplate}\n\n";

// Check if template file exists and has sisa cuti code
$templatePath = resource_path("views/" . str_replace('.', '/', $selectedTemplate) . ".blade.php");
echo "4. Template File Check:\n";
echo "   Template Path: {$templatePath}\n";
echo "   File Exists: " . (file_exists($templatePath) ? 'YES' : 'NO') . "\n";

if (file_exists($templatePath)) {
    $templateContent = file_get_contents($templatePath);
    $hasSisaCutiCode = strpos($templateContent, 'getSisaCutiMultiYear') !== false;
    echo "   Has getSisaCutiMultiYear: " . ($hasSisaCutiCode ? 'YES' : 'NO') . "\n";
    
    if (!$hasSisaCutiCode) {
        echo "   ❌ PROBLEM: Template doesn't have sisa cuti code!\n";
        
        // Show what's in the template around CUTI TAHUNAN
        $lines = explode("\n", $templateContent);
        echo "   Looking for CUTI TAHUNAN section:\n";
        foreach ($lines as $lineNum => $line) {
            if (stripos($line, 'CUTI TAHUNAN') !== false) {
                $start = max(0, $lineNum - 3);
                $end = min(count($lines) - 1, $lineNum + 10);
                echo "   Found at line " . ($lineNum + 1) . ":\n";
                for ($i = $start; $i <= $end; $i++) {
                    $marker = ($i == $lineNum) ? '>>> ' : '    ';
                    echo "   {$marker}" . ($i + 1) . ": " . trim($lines[$i]) . "\n";
                }
                break;
            }
        }
    }
} else {
    echo "   ❌ PROBLEM: Template file not found!\n";
}

echo "\n5. Database Check - Sisa Cuti Records:\n";
$sisaCutiRecords = \App\Models\SisaCuti::where('user_id', $testUser->id)->get();
echo "   Found {$sisaCutiRecords->count()} records for user {$testUser->id}:\n";

foreach ($sisaCutiRecords as $record) {
    echo "     {$record->tahun}: Awal={$record->sisa_awal}, Diambil={$record->cuti_diambil}, Sisa={$record->sisa_akhir}\n";
}

if ($sisaCutiRecords->count() == 0) {
    echo "   ❌ PROBLEM: No sisa cuti records found!\n";
    echo "   Creating test data...\n";
    
    foreach ([2023, 2024, 2025] as $tahun) {
        $cutiDiambil = ($tahun == 2025) ? 2 : rand(0, 5);
        \App\Models\SisaCuti::create([
            'user_id' => $testUser->id,
            'tahun' => $tahun,
            'sisa_awal' => 12,
            'cuti_diambil' => $cutiDiambil,
            'sisa_akhir' => 12 - $cutiDiambil,
            'keterangan' => "Debug test data",
            'is_active' => true
        ]);
        echo "     Created {$tahun}: 12 - {$cutiDiambil} = " . (12 - $cutiDiambil) . "\n";
    }
}

echo "\n6. Testing getSisaCutiMultiYear Method:\n";
$sisaCutiData = \App\Models\SisaCuti::getSisaCutiMultiYear($testUser->id, [2023, 2024, 2025]);
echo "   Method result:\n";
foreach ($sisaCutiData as $tahun => $sisa) {
    echo "     {$tahun}: " . ($sisa !== null ? $sisa : 'NULL') . "\n";
}

if (array_filter($sisaCutiData, function($v) { return $v !== null; }) == []) {
    echo "   ❌ PROBLEM: getSisaCutiMultiYear returns all NULL!\n";
    
    // Debug the method
    echo "   Debugging method step by step:\n";
    foreach ([2023, 2024, 2025] as $tahun) {
        $record = \App\Models\SisaCuti::where('user_id', $testUser->id)
                                     ->where('tahun', $tahun)
                                     ->where('is_active', true)
                                     ->first();
        echo "     {$tahun}: " . ($record ? "Found (sisa_akhir={$record->sisa_akhir})" : 'Not found') . "\n";
    }
}

echo "\n7. Create Test Surat Cuti:\n";
$jenisCuti = \App\Models\JenisCuti::first();
$testSurat = \App\Models\SuratCuti::create([
    'pengaju_id' => $testUser->id,
    'jenis_cuti_id' => $jenisCuti->id,
    'tanggal_awal' => now()->addDays(7),
    'tanggal_akhir' => now()->addDays(8), // 2 days
    'alasan' => 'Debug test - should be 12-2=10',
    'status' => 'disetujui',
    'tanggal_ajuan' => now()
]);

echo "   Surat Cuti ID: {$testSurat->id}\n";
echo "   Pengaju ID: {$testSurat->pengaju_id}\n";
echo "   Duration: {$testSurat->jumlah_hari} hari\n";

// Create disposisi
\App\Models\DisposisiCuti::create([
    'surat_cuti_id' => $testSurat->id,
    'user_id' => $testUser->id,
    'jabatan' => 'KADIN',
    'tipe_disposisi' => 'ttd',
    'status' => 'sudah',
    'tanggal' => now(),
    'catatan' => 'Debug test'
]);

echo "\n8. Test Template Data Preparation:\n";
try {
    $disposisiList = $testSurat->disposisiCuti()->get();
    
    $enhanceDataMethod = $reflection->getMethod('enhanceDataForTemplate');
    $enhanceDataMethod->setAccessible(true);
    
    $baseData = [
        'suratCuti' => $testSurat,
        'disposisiList' => $disposisiList,
        'isFlexibleApproval' => false,
        'completionRate' => ['overall' => 100]
    ];
    
    $enhancedData = $enhanceDataMethod->invoke($controller, $baseData, $testUser->unit_kerja);
    
    echo "   Enhanced data keys: " . implode(', ', array_keys($enhancedData)) . "\n";
    
    // Check specific variables
    if (isset($enhancedData['kadinSignature'])) {
        echo "   kadinSignature: " . ($enhancedData['kadinSignature'] ? 'SET' : 'NULL') . "\n";
    } else {
        echo "   kadinSignature: NOT SET\n";
    }
    
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n9. Test Exact Template Code:\n";
echo "   Testing the exact code that will run in template:\n";
echo "   \$suratCuti->pengaju_id = {$testSurat->pengaju_id}\n";

// This is EXACTLY what runs in the template
$templateSisaCutiData = \App\Models\SisaCuti::getSisaCutiMultiYear($testSurat->pengaju_id, [2023, 2024, 2025]);
echo "   Template getSisaCutiMultiYear result:\n";
foreach ([2023, 2024, 2025] as $tahun) {
    $sisa = $templateSisaCutiData[$tahun] ?? null;
    $fallback = ($tahun == 2025) ? '12' : '';
    $finalValue = $sisa ?? $fallback;
    
    echo "     {$tahun}: raw={$sisa}, fallback='{$fallback}', final='{$finalValue}'\n";
}

echo "\n10. Generate Test PDF:\n";
try {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($selectedTemplate, $enhancedData);
    $pdf->setPaper('A4', 'portrait');
    
    $testPdfPath = public_path('debug_super_detail.pdf');
    $pdf->save($testPdfPath);
    
    echo "   ✅ PDF generated: {$testPdfPath}\n";
    echo "   📊 Size: " . number_format(filesize($testPdfPath) / 1024, 2) . " KB\n";
    
} catch (\Exception $e) {
    echo "   ❌ PDF failed: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              DIAGNOSIS                                      ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🔍 POTENTIAL ISSUES:\n";

if (!file_exists($templatePath)) {
    echo "❌ Template file missing\n";
} elseif (!strpos(file_get_contents($templatePath), 'getSisaCutiMultiYear')) {
    echo "❌ Template doesn't have sisa cuti code\n";
} elseif ($sisaCutiRecords->count() == 0) {
    echo "❌ No sisa cuti data in database\n";
} elseif (array_filter($sisaCutiData, function($v) { return $v !== null; }) == []) {
    echo "❌ getSisaCutiMultiYear returns all NULL\n";
} else {
    echo "✅ Everything looks good - check the PDF!\n";
}

// Cleanup
\App\Models\DisposisiCuti::where('surat_cuti_id', $testSurat->id)->delete();
$testSurat->delete();

echo "\n📝 Please check debug_super_detail.pdf and tell me what you see!\n";
