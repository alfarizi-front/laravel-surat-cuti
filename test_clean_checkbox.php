<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST CLEAN CHECKBOX FORMAT                              ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🧹 PEMBERSIHAN YANG DILAKUKAN:\n";
echo "   • Hapus CSS checkbox yang kompleks\n";
echo "   • Ganti dengan @if/@else yang sederhana\n";
echo "   • Format: [X] untuk dipilih, [ ] untuk tidak dipilih\n";
echo "   • Gunakan <strong> untuk [X] agar lebih tebal\n\n";

// Test dengan surat yang ada
$realSurat = \App\Models\SuratCuti::with(['jenisCuti', 'pengaju'])->latest()->first();

if ($realSurat) {
    echo "1. Testing dengan Surat Real:\n";
    echo "   Surat #{$realSurat->id}\n";
    echo "   Jenis Cuti: '{$realSurat->jenisCuti->nama}'\n";
    echo "   Unit Kerja: {$realSurat->pengaju->unit_kerja}\n\n";
    
    echo "2. Expected Output:\n";
    $checkboxTests = [
        'Cuti Tahunan' => stripos($realSurat->jenisCuti->nama, 'Tahunan') !== false,
        'Cuti Besar' => stripos($realSurat->jenisCuti->nama, 'Besar') !== false,
        'Cuti Sakit' => stripos($realSurat->jenisCuti->nama, 'Sakit') !== false,
        'Cuti Melahirkan' => stripos($realSurat->jenisCuti->nama, 'Melahirkan') !== false,
        'Cuti Alasan Penting' => stripos($realSurat->jenisCuti->nama, 'Alasan Penting') !== false,
        'Cuti di Luar Tanggungan Negara' => stripos($realSurat->jenisCuti->nama, 'Luar Tanggungan') !== false,
    ];
    
    foreach ($checkboxTests as $label => $isChecked) {
        if ($isChecked) {
            echo "   [X] {$label} ← SHOULD BE BOLD\n";
        } else {
            echo "   [ ] {$label}\n";
        }
    }
    
    echo "\n3. Generating Clean PDF:\n";
    
    try {
        $controller = new \App\Http\Controllers\SuratCutiController();
        $reflection = new ReflectionClass($controller);
        
        $selectTemplateMethod = $reflection->getMethod('selectPDFTemplate');
        $selectTemplateMethod->setAccessible(true);
        $selectedTemplate = $selectTemplateMethod->invoke($controller, $realSurat->pengaju->unit_kerja);
        
        echo "   Template: {$selectedTemplate}\n";
        
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
        
        $cleanPdfPath = public_path('test_clean_checkbox.pdf');
        $pdf->save($cleanPdfPath);
        
        echo "   ✅ PDF: test_clean_checkbox.pdf\n";
        echo "   📊 Size: " . number_format(filesize($cleanPdfPath) / 1024, 2) . " KB\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n4. Creating HTML Preview:\n";

$previewHTML = '
<!DOCTYPE html>
<html>
<head>
    <title>Clean Checkbox Preview</title>
    <style>
        body { font-family: Arial; padding: 20px; line-height: 1.6; }
        .success { background: #e6ffe6; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .before-after { display: flex; gap: 20px; margin: 20px 0; }
        .version { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .checkbox-clean { font-family: monospace; margin-right: 8px; }
        .selected { font-weight: bold; color: #000; }
    </style>
</head>
<body>
    <h1>🧹 Clean Checkbox Implementation</h1>
    
    <div class="success">
        <h2>🎯 Final Solution</h2>
        <p><strong>Problem:</strong> Karakter aneh dan tanda tanya muncul di PDF</p>
        <p><strong>Solution:</strong> Format sederhana dengan @if/@else dan ASCII characters</p>
    </div>
    
    <div class="before-after">
        <div class="version">
            <h3>❌ Before (Complex)</h3>
            <pre>
&lt;span class="checkbox {{ ... }}"&gt;
{{ ... ? "✓" : "" }}
&lt;/span&gt; Cuti Tahunan
            </pre>
            <p><small>Menggunakan CSS dan Unicode yang bisa bermasalah</small></p>
        </div>
        
        <div class="version">
            <h3>✅ After (Clean)</h3>
            <pre>
@if(stripos(..., "Tahunan") !== false)
    &lt;strong&gt;[X]&lt;/strong&gt; Cuti Tahunan
@else
    [ ] Cuti Tahunan
@endif
            </pre>
            <p><small>Simple, clean, dan pasti bekerja</small></p>
        </div>
    </div>
    
    <h2>📋 Preview dengan Jenis Cuti: ' . ($realSurat ? $realSurat->jenisCuti->nama : 'N/A') . '</h2>
    
    <table>
        <tr>
            <th>No.</th>
            <th>Jenis Cuti</th>
            <th>Display</th>
        </tr>';

if ($realSurat) {
    $no = 1;
    foreach ($checkboxTests as $label => $isChecked) {
        $display = $isChecked ? '<span class="checkbox-clean selected">[X]</span>' : '<span class="checkbox-clean">[ ]</span>';
        $previewHTML .= "
        <tr>
            <td>{$no}.</td>
            <td>{$label}</td>
            <td>{$display} {$label}</td>
        </tr>";
        $no++;
    }
}

$previewHTML .= '
    </table>
    
    <div class="success">
        <h3>✅ What to Expect</h3>
        <ul>
            <li><strong>[X]</strong> akan muncul tebal untuk jenis cuti yang dipilih</li>
            <li><strong>[ ]</strong> akan muncul normal untuk yang tidak dipilih</li>
            <li>Tidak ada lagi karakter aneh atau tanda tanya</li>
            <li>Format konsisten di semua template</li>
        </ul>
    </div>
    
    <h3>🔧 Technical Changes</h3>
    <ul>
        <li>Removed complex CSS checkbox styling</li>
        <li>Replaced with simple @if/@else Blade directives</li>
        <li>Used ASCII characters: [X] and [ ]</li>
        <li>Applied &lt;strong&gt; tag for selected items</li>
        <li>Updated all 3 templates: Puskesmas ASN, Sekretariat, Bidang</li>
    </ul>
</body>
</html>
';

file_put_contents(public_path('clean_checkbox_preview.html'), $previewHTML);
echo "   ✅ Preview: clean_checkbox_preview.html\n";
echo "   🌐 View: http://localhost/clean_checkbox_preview.html\n";

echo "\n╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              CLEAN SOLUTION APPLIED!                       ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🎉 FINAL IMPLEMENTATION:\n";
echo "   • Hapus semua CSS checkbox yang kompleks\n";
echo "   • Gunakan @if/@else Blade directive yang sederhana\n";
echo "   • Format: [X] untuk dipilih (dengan <strong>), [ ] untuk tidak dipilih\n";
echo "   • Hanya ASCII characters yang pasti didukung semua font\n";
echo "   • Applied ke semua template\n\n";

echo "📄 EXPECTED RESULT:\n";
echo "   • PDF menampilkan [X] tebal untuk jenis cuti yang dipilih\n";
echo "   • PDF menampilkan [ ] normal untuk yang tidak dipilih\n";
echo "   • Tidak ada lagi karakter aneh atau tanda tanya\n";
echo "   • Format bersih dan konsisten\n\n";

echo "🔍 VERIFICATION:\n";
echo "   1. Buka test_clean_checkbox.pdf\n";
echo "   2. Cari section 'II. JENIS CUTI YANG DIAMBIL'\n";
echo "   3. Pastikan [X] muncul tebal di jenis cuti yang benar\n";
echo "   4. Pastikan [ ] muncul di jenis cuti lainnya\n\n";

echo "✅ Sekarang harusnya bersih tanpa karakter aneh! 😄\n";
echo "   Kalau masih ada yang nyelip, berarti ada masalah di tempat lain.\n";
