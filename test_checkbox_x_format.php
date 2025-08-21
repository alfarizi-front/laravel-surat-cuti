<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST CHECKBOX [X] FORMAT                                ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

// Test dengan surat yang ada
$realSurat = \App\Models\SuratCuti::with(['jenisCuti', 'pengaju'])->latest()->first();

if ($realSurat) {
    echo "1. Testing with Real Surat:\n";
    echo "   Surat #{$realSurat->id} - Jenis: '{$realSurat->jenisCuti->nama}'\n";
    echo "   Unit Kerja: {$realSurat->pengaju->unit_kerja}\n\n";
    
    // Test checkbox logic
    $checkboxTests = [
        'Cuti Tahunan' => stripos($realSurat->jenisCuti->nama, 'Tahunan') !== false,
        'Cuti Besar' => stripos($realSurat->jenisCuti->nama, 'Besar') !== false,
        'Cuti Sakit' => stripos($realSurat->jenisCuti->nama, 'Sakit') !== false,
        'Cuti Melahirkan' => stripos($realSurat->jenisCuti->nama, 'Melahirkan') !== false,
        'Cuti Alasan Penting' => stripos($realSurat->jenisCuti->nama, 'Alasan Penting') !== false,
        'Cuti di Luar Tanggungan Negara' => stripos($realSurat->jenisCuti->nama, 'Luar Tanggungan') !== false,
    ];
    
    echo "2. Expected Checkbox Results:\n";
    foreach ($checkboxTests as $label => $isChecked) {
        $display = $isChecked ? '[X]' : '[ ]';
        echo "   {$display} {$label}\n";
    }
    
    echo "\n3. Generating Test PDF:\n";
    
    try {
        $controller = new \App\Http\Controllers\SuratCutiController();
        $reflection = new ReflectionClass($controller);
        
        $selectTemplateMethod = $reflection->getMethod('selectPDFTemplate');
        $selectTemplateMethod->setAccessible(true);
        $selectedTemplate = $selectTemplateMethod->invoke($controller, $realSurat->pengaju->unit_kerja);
        
        echo "   Selected template: {$selectedTemplate}\n";
        
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
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($selectedTemplate, $enhancedData);
        $pdf->setPaper('A4', 'portrait');
        
        $testPdfPath = public_path('test_checkbox_x_format.pdf');
        $pdf->save($testPdfPath);
        
        echo "   ✅ PDF generated: test_checkbox_x_format.pdf\n";
        echo "   📊 Size: " . number_format(filesize($testPdfPath) / 1024, 2) . " KB\n";
        
    } catch (\Exception $e) {
        echo "   ❌ PDF generation failed: " . $e->getMessage() . "\n";
    }
}

echo "\n4. Creating HTML Preview with [X] Format:\n";

$previewHTML = '
<!DOCTYPE html>
<html>
<head>
    <title>[X] Checkbox Format Preview</title>
    <style>
        body { font-family: Arial; padding: 20px; line-height: 1.6; }
        .success { background: #e6ffe6; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .comparison { display: flex; gap: 20px; margin: 20px 0; }
        .method { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .checkbox-old { 
            width: 15px; height: 15px; border: 2px solid #000; display: inline-block;
            margin-right: 8px; vertical-align: middle; text-align: center;
            line-height: 11px; font-weight: bold; background-color: white;
        }
        .checkbox-new { 
            font-family: monospace; font-weight: bold; margin-right: 8px;
            color: #000; font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>📋 Checkbox Format Comparison</h1>
    
    <div class="success">
        <h2>🎯 Problem & Solution</h2>
        <p><strong>Problem:</strong> ✓ symbol tidak muncul di PDF (mungkin font issue)</p>
        <p><strong>Solution:</strong> Ganti dengan [X] dan [ ] yang lebih kompatibel</p>
    </div>
    
    <div class="comparison">
        <div class="method">
            <h3>❌ Old Method (✓ symbol)</h3>
            <table>
                <tr>
                    <td>1.</td>
                    <td><span class="checkbox-old">✓</span> Cuti Tahunan</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><span class="checkbox-old"></span> Cuti Besar</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td><span class="checkbox-old"></span> Cuti Sakit</td>
                </tr>
            </table>
            <p><small>Mungkin tidak muncul di PDF karena font issue</small></p>
        </div>
        
        <div class="method">
            <h3>✅ New Method ([X] format)</h3>
            <table>
                <tr>
                    <td>1.</td>
                    <td><span class="checkbox-new">[X]</span> Cuti Tahunan</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><span class="checkbox-new">[ ]</span> Cuti Besar</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td><span class="checkbox-new">[ ]</span> Cuti Sakit</td>
                </tr>
            </table>
            <p><small>Menggunakan karakter ASCII yang pasti didukung</small></p>
        </div>
    </div>
    
    <h2>📋 Test dengan Jenis Cuti: ' . ($realSurat ? $realSurat->jenisCuti->nama : 'N/A') . '</h2>
    
    <table>
        <tr>
            <th>No.</th>
            <th>Jenis Cuti</th>
            <th>Status</th>
            <th>Display</th>
        </tr>';

if ($realSurat) {
    $checkboxTests = [
        'Cuti Tahunan' => stripos($realSurat->jenisCuti->nama, 'Tahunan') !== false,
        'Cuti Besar' => stripos($realSurat->jenisCuti->nama, 'Besar') !== false,
        'Cuti Sakit' => stripos($realSurat->jenisCuti->nama, 'Sakit') !== false,
        'Cuti Melahirkan' => stripos($realSurat->jenisCuti->nama, 'Melahirkan') !== false,
        'Cuti Alasan Penting' => stripos($realSurat->jenisCuti->nama, 'Alasan Penting') !== false,
        'Cuti di Luar Tanggungan Negara' => stripos($realSurat->jenisCuti->nama, 'Luar Tanggungan') !== false,
    ];
    
    $no = 1;
    foreach ($checkboxTests as $label => $isChecked) {
        $status = $isChecked ? 'Selected' : 'Not selected';
        $display = $isChecked ? '[X]' : '[ ]';
        $previewHTML .= "
        <tr>
            <td>{$no}.</td>
            <td>{$label}</td>
            <td>{$status}</td>
            <td><span class=\"checkbox-new\">{$display}</span></td>
        </tr>";
        $no++;
    }
}

$previewHTML .= '
    </table>
    
    <div class="success">
        <h3>✅ Verification Steps</h3>
        <ol>
            <li>Open <strong>test_checkbox_x_format.pdf</strong></li>
            <li>Look for section "II. JENIS CUTI YANG DIAMBIL"</li>
            <li>Check if selected jenis cuti shows <strong>[X]</strong></li>
            <li>Check if other options show <strong>[ ]</strong></li>
        </ol>
        <p>If you see [X] and [ ] correctly, the problem is solved! 🎉</p>
    </div>
    
    <h3>🔧 Technical Details</h3>
    <ul>
        <li><strong>Before:</strong> <code>{{ stripos(...) ? "✓" : "" }}</code></li>
        <li><strong>After:</strong> <code>{{ stripos(...) ? "[X]" : "[ ]" }}</code></li>
        <li><strong>Benefit:</strong> ASCII characters work in all fonts and PDF renderers</li>
        <li><strong>Applied to:</strong> Puskesmas ASN, Sekretariat, and Bidang templates</li>
    </ul>
</body>
</html>
';

file_put_contents(public_path('checkbox_x_format_preview.html'), $previewHTML);
echo "   ✅ Created preview: checkbox_x_format_preview.html\n";
echo "   🌐 View at: http://localhost/checkbox_x_format_preview.html\n";

echo "\n╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              [X] FORMAT APPLIED!                           ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🎯 PERUBAHAN YANG DILAKUKAN:\n";
echo "   • Ganti ✓ dengan [X] untuk jenis cuti yang dipilih\n";
echo "   • Ganti kosong dengan [ ] untuk jenis cuti yang tidak dipilih\n";
echo "   • Menggunakan karakter ASCII yang pasti didukung semua font\n";
echo "   • Applied ke semua template: Puskesmas ASN, Sekretariat, Bidang\n\n";

echo "📄 EXPECTED RESULT:\n";
echo "   • PDF sekarang harus menampilkan [X] untuk jenis cuti yang dipilih\n";
echo "   • Jenis cuti lain menampilkan [ ]\n";
echo "   • Tidak ada lagi tanda tanya (?)\n\n";

echo "🔍 VERIFICATION:\n";
echo "   1. Buka test_checkbox_x_format.pdf\n";
echo "   2. Cari section 'II. JENIS CUTI YANG DIAMBIL'\n";
echo "   3. Pastikan ada [X] di jenis cuti yang benar\n";
echo "   4. Pastikan yang lain menampilkan [ ]\n\n";

echo "✅ Sekarang seharusnya tidak ada lagi tanda tanya! 😄\n";
echo "   Kalau masih ada masalah, berarti ada issue lain yang perlu di-debug.\n";
