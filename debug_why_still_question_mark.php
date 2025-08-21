<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    DEBUG: KENAPA MASIH TANDA TANYA? 😅                     ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "1. Checking Real Surat Cuti Data:\n";

$realSurat = \App\Models\SuratCuti::with(['jenisCuti', 'pengaju'])->latest()->first();

if ($realSurat) {
    echo "   Real surat #{$realSurat->id}:\n";
    echo "     Pengaju: {$realSurat->pengaju->nama}\n";
    echo "     Unit Kerja: {$realSurat->pengaju->unit_kerja}\n";
    echo "     Jenis Cuti: '{$realSurat->jenisCuti->nama}'\n";
    echo "     Status: {$realSurat->status}\n";
    
    // Test checkbox logic dengan data real
    echo "\n   Testing checkbox logic with real data:\n";
    $jenisCutiNama = $realSurat->jenisCuti->nama;
    
    $checkboxTests = [
        'Cuti Tahunan' => stripos($jenisCutiNama, 'Tahunan') !== false,
        'Cuti Besar' => stripos($jenisCutiNama, 'Besar') !== false,
        'Cuti Sakit' => stripos($jenisCutiNama, 'Sakit') !== false,
        'Cuti Melahirkan' => stripos($jenisCutiNama, 'Melahirkan') !== false,
        'Cuti Alasan Penting' => stripos($jenisCutiNama, 'Alasan Penting') !== false,
        'Cuti di Luar Tanggungan Negara' => stripos($jenisCutiNama, 'Luar Tanggungan') !== false,
    ];
    
    foreach ($checkboxTests as $label => $isChecked) {
        $status = $isChecked ? '✓ SHOULD BE CHECKED' : '☐ should be empty';
        echo "     {$label}: {$status}\n";
    }
    
} else {
    echo "   ❌ No surat cuti found in database\n";
}

echo "\n2. Checking Template Selection Logic:\n";

if ($realSurat) {
    $controller = new \App\Http\Controllers\SuratCutiController();
    $reflection = new ReflectionClass($controller);
    
    $selectTemplateMethod = $reflection->getMethod('selectPDFTemplate');
    $selectTemplateMethod->setAccessible(true);
    $selectedTemplate = $selectTemplateMethod->invoke($controller, $realSurat->pengaju->unit_kerja);
    
    echo "   Unit Kerja: {$realSurat->pengaju->unit_kerja}\n";
    echo "   Selected Template: {$selectedTemplate}\n";
    
    // Check if selected template has checkbox section
    $templatePath = resource_path("views/{$selectedTemplate}.blade.php");
    if (file_exists($templatePath)) {
        $templateContent = file_get_contents($templatePath);
        $hasJenisCutiSection = strpos($templateContent, 'JENIS CUTI YANG DIAMBIL') !== false;
        $hasCheckboxClass = strpos($templateContent, 'class="checkbox') !== false;
        $hasStripos = strpos($templateContent, 'stripos') !== false;
        $hasDirectCheckmark = strpos($templateContent, '✓') !== false;
        
        echo "   Template Analysis:\n";
        echo "     - Has 'JENIS CUTI YANG DIAMBIL' section: " . ($hasJenisCutiSection ? 'YES' : 'NO') . "\n";
        echo "     - Has checkbox class: " . ($hasCheckboxClass ? 'YES' : 'NO') . "\n";
        echo "     - Uses stripos(): " . ($hasStripos ? 'YES' : 'NO') . "\n";
        echo "     - Has direct ✓: " . ($hasDirectCheckmark ? 'YES' : 'NO') . "\n";
        
        if (!$hasJenisCutiSection) {
            echo "   ❌ PROBLEM FOUND: Template doesn't have checkbox section!\n";
        }
    } else {
        echo "   ❌ Template file not found: {$templatePath}\n";
    }
}

echo "\n3. Creating Test PDF with Debug Info:\n";

if ($realSurat) {
    try {
        // Generate PDF dengan data real
        $disposisiList = $realSurat->disposisiCuti()->get();
        
        $enhanceDataMethod = $reflection->getMethod('enhanceDataForTemplate');
        $enhanceDataMethod->setAccessible(true);
        
        $baseData = [
            'suratCuti' => $realSurat,
            'disposisiList' => $disposisiList,
            'isFlexibleApproval' => false,
            'completionRate' => ['overall' => 100, 'signatures' => 100, 'parafs' => 100]
        ];
        
        $enhancedData = $enhanceDataMethod->invoke($controller, $baseData, $realSurat->pengaju->unit_kerja);
        
        // Add debug info to data
        $enhancedData['debug_jenis_cuti'] = $realSurat->jenisCuti->nama;
        $enhancedData['debug_checkbox_tests'] = $checkboxTests;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($selectedTemplate, $enhancedData);
        $pdf->setPaper('A4', 'portrait');
        
        $debugPdfPath = public_path('debug_real_surat.pdf');
        $pdf->save($debugPdfPath);
        
        echo "   ✅ Debug PDF generated: debug_real_surat.pdf\n";
        echo "   📊 Size: " . number_format(filesize($debugPdfPath) / 1024, 2) . " KB\n";
        
    } catch (\Exception $e) {
        echo "   ❌ PDF generation failed: " . $e->getMessage() . "\n";
    }
}

echo "\n4. Checking CSS Rendering:\n";

// Create simple HTML test with exact same CSS
$cssTestHTML = '
<!DOCTYPE html>
<html>
<head>
    <title>CSS Checkbox Test</title>
    <style>
        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
            background-color: white;
        }
        
        .checkbox.checked {
            background-color: white;
            color: black;
            font-weight: bold;
        }
        
        table { border-collapse: collapse; width: 100%; }
        td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h1>CSS Checkbox Test</h1>
    
    <h2>Method 1: CSS ::after (original)</h2>
    <style>
        .checkbox-method1.checked::after {
            content: "✓";
            display: block;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
        }
    </style>
    <table>
        <tr>
            <td>1.</td>
            <td><span class="checkbox checkbox-method1 checked"></span> Cuti Tahunan (CSS ::after)</td>
        </tr>
        <tr>
            <td>2.</td>
            <td><span class="checkbox checkbox-method1"></span> Cuti Besar (empty)</td>
        </tr>
    </table>
    
    <h2>Method 2: Direct content (new)</h2>
    <table>
        <tr>
            <td>1.</td>
            <td><span class="checkbox checked">✓</span> Cuti Tahunan (direct ✓)</td>
        </tr>
        <tr>
            <td>2.</td>
            <td><span class="checkbox"></span> Cuti Besar (empty)</td>
        </tr>
    </table>
    
    <h2>Method 3: Unicode alternatives</h2>
    <table>
        <tr>
            <td>1.</td>
            <td><span class="checkbox checked">&#10003;</span> Cuti Tahunan (&#10003;)</td>
        </tr>
        <tr>
            <td>2.</td>
            <td><span class="checkbox checked">&#x2713;</span> Cuti Sakit (&#x2713;)</td>
        </tr>
        <tr>
            <td>3.</td>
            <td><span class="checkbox checked">X</span> Cuti Melahirkan (X)</td>
        </tr>
    </table>
    
    <h2>Method 4: Background approach</h2>
    <style>
        .checkbox-bg.checked {
            background-color: black;
            color: white;
        }
    </style>
    <table>
        <tr>
            <td>1.</td>
            <td><span class="checkbox checkbox-bg checked">✓</span> Cuti Tahunan (black bg)</td>
        </tr>
    </table>
</body>
</html>
';

file_put_contents(public_path('css_checkbox_test.html'), $cssTestHTML);
echo "   ✅ Created CSS test: css_checkbox_test.html\n";
echo "   🌐 Test at: http://localhost/css_checkbox_test.html\n";

echo "\n5. Possible Issues:\n";

echo "   🤔 KEMUNGKINAN MASALAH:\n";
echo "     1. PDF renderer tidak support ✓ symbol\n";
echo "     2. Font tidak memiliki ✓ character\n";
echo "     3. CSS ::after tidak bekerja di PDF\n";
echo "     4. Template yang digunakan bukan yang sudah diupdate\n";
echo "     5. Encoding issue dengan ✓ symbol\n\n";

echo "   💡 SOLUSI ALTERNATIF:\n";
echo "     1. Ganti ✓ dengan X atau [X]\n";
echo "     2. Gunakan background hitam untuk checkbox\n";
echo "     3. Gunakan unicode &#10003; atau &#x2713;\n";
echo "     4. Gunakan gambar/border untuk checkbox\n\n";

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              NEXT DEBUGGING STEPS                          ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🔍 LANGKAH DEBUGGING:\n";
echo "   1. Buka debug_real_surat.pdf - lihat apakah ada section jenis cuti\n";
echo "   2. Buka css_checkbox_test.html - lihat method mana yang bekerja\n";
echo "   3. Kalau masih ?, coba ganti ✓ dengan X atau [X]\n";
echo "   4. Test dengan template yang berbeda\n\n";

echo "📝 REPORT BACK:\n";
echo "   1. Apakah debug_real_surat.pdf punya section 'JENIS CUTI YANG DIAMBIL'?\n";
echo "   2. Di css_checkbox_test.html, method mana yang menampilkan checkmark?\n";
echo "   3. Template apa yang digunakan untuk surat terakhir?\n\n";

echo "✅ Debug analysis complete! Mari kita cari tahu kenapa masih ? 😅\n";
