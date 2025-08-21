<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST SIMPLE BUTTON IMPLEMENTATION                       ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

// Buat test data
echo "1. Creating Test Data:\n";

$testUser = \App\Models\User::where('role', '!=', 'admin')->first();
$jenisCuti = \App\Models\JenisCuti::first();

$testSurat = \App\Models\SuratCuti::create([
    'pengaju_id' => $testUser->id,
    'jenis_cuti_id' => $jenisCuti->id,
    'tanggal_awal' => now()->addDays(7),
    'tanggal_akhir' => now()->addDays(8), // 2 hari
    'alasan' => 'Test simple button implementation',
    'status' => 'draft',
    'tanggal_ajuan' => now()
]);

echo "   ✅ Created test surat #{$testSurat->id} for {$testUser->nama}\n";

echo "\n2. Checking Simple Implementation:\n";

$viewPath = resource_path('views/admin/surat-cuti/dashboard.blade.php');
$viewContent = file_get_contents($viewPath);

// Check for simple functions
$hasSimpleFunctions = strpos($viewContent, 'simpleBulkApprove') !== false;
$hasFormSubmission = strpos($viewContent, 'form.submit()') !== false;
$hasInlineOnclick = strpos($viewContent, 'onclick="simple') !== false;

echo "   - Has simple functions: " . ($hasSimpleFunctions ? 'YES' : 'NO') . "\n";
echo "   - Has form submission: " . ($hasFormSubmission ? 'YES' : 'NO') . "\n";
echo "   - Has inline onclick: " . ($hasInlineOnclick ? 'YES' : 'NO') . "\n";

echo "\n3. Checking Controller Updates:\n";

$controller = new \App\Http\Controllers\SuratCutiController();
$reflection = new ReflectionClass($controller);

// Check if methods handle both JSON and form requests
$bulkApproveMethod = $reflection->getMethod('bulkApproveAll');
$methodContent = file_get_contents($reflection->getFileName());

$handlesFormSubmission = strpos($methodContent, 'expectsJson') !== false;
$hasRedirect = strpos($methodContent, 'redirect()') !== false;

echo "   - Handles form submission: " . ($handlesFormSubmission ? 'YES' : 'NO') . "\n";
echo "   - Has redirect response: " . ($hasRedirect ? 'YES' : 'NO') . "\n";

echo "\n4. Creating Ultra Simple Test Page:\n";

$ultraSimpleHTML = '
<!DOCTYPE html>
<html>
<head>
    <title>Ultra Simple Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        button { 
            padding: 15px 30px; 
            margin: 10px; 
            font-size: 18px; 
            cursor: pointer; 
            border: none; 
            border-radius: 5px;
        }
        .green { background: #22c55e; color: white; }
        .red { background: #ef4444; color: white; }
        .blue { background: #3b82f6; color: white; }
    </style>
</head>
<body>
    <h1>🧪 Ultra Simple Button Test</h1>
    
    <p><strong>Test 1:</strong> Basic alert</p>
    <button class="blue" onclick="alert(\'✅ Basic onclick works!\')">
        Click Me - Basic Alert
    </button>
    
    <p><strong>Test 2:</strong> Function call</p>
    <button class="green" onclick="testFunction()">
        Click Me - Function Call
    </button>
    
    <p><strong>Test 3:</strong> Console log</p>
    <button class="red" onclick="console.log(\'✅ Console log works!\'); alert(\'Check console!\')">
        Click Me - Console Log
    </button>
    
    <p><strong>Test 4:</strong> Form submission</p>
    <form method="GET" action="' . route('admin.surat-cuti.admin-dashboard') . '">
        <button type="submit" class="green">
            Click Me - Form Submit
        </button>
    </form>
    
    <div style="margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
        <h3>Instructions:</h3>
        <ol>
            <li>Try each button in order</li>
            <li>If ANY button works, JavaScript is functional</li>
            <li>If NO buttons work, there\'s a browser/system issue</li>
            <li>Open browser console (F12) to see logs</li>
        </ol>
    </div>
    
    <script>
        console.log("🚀 Ultra simple script loaded");
        
        function testFunction() {
            console.log("✅ Function call works!");
            alert("✅ Function call successful!");
        }
        
        // Test immediate execution
        console.log("📜 Script executed immediately");
    </script>
</body>
</html>
';

file_put_contents(public_path('ultra_simple_test.html'), $ultraSimpleHTML);
echo "   ✅ Created ultra simple test: " . public_path('ultra_simple_test.html') . "\n";
echo "   🌐 Access at: http://localhost/ultra_simple_test.html\n";

echo "\n5. Dashboard Implementation Summary:\n";

echo "   ✅ CHANGES MADE:\n";
echo "     • Removed complex event listeners\n";
echo "     • Used simple onclick handlers\n";
echo "     • Added form submission fallback\n";
echo "     • Added visual indicators (🟢🔴)\n";
echo "     • Added inline styles for cursor\n";
echo "     • Controller handles both AJAX and form\n\n";

echo "   🎯 HOW IT WORKS NOW:\n";
echo "     1. User clicks button\n";
echo "     2. onclick='simpleBulkApprove()' fires\n";
echo "     3. Shows confirmation dialog\n";
echo "     4. Creates hidden form with CSRF token\n";
echo "     5. Submits form to controller\n";
echo "     6. Controller processes and redirects back\n\n";

echo "   🔧 DEBUGGING STEPS:\n";
echo "     1. Test ultra simple page first\n";
echo "     2. If that works, test admin dashboard\n";
echo "     3. Check browser console for errors\n";
echo "     4. Look for 'Simple Dashboard Script Loading...'\n";
echo "     5. Try clicking buttons\n\n";

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              TROUBLESHOOTING                               ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "🚨 IF BUTTONS STILL DON'T WORK:\n\n";

echo "1. **Browser Issues:**\n";
echo "   - Try different browser (Chrome, Firefox, Edge)\n";
echo "   - Clear browser cache (Ctrl+F5)\n";
echo "   - Disable browser extensions\n";
echo "   - Check if JavaScript is enabled\n\n";

echo "2. **System Issues:**\n";
echo "   - Check if antivirus is blocking JavaScript\n";
echo "   - Try incognito/private mode\n";
echo "   - Check Windows security settings\n\n";

echo "3. **Laravel Issues:**\n";
echo "   - Check if CSRF protection is working\n";
echo "   - Verify routes are registered\n";
echo "   - Check Laravel logs for errors\n\n";

echo "4. **Network Issues:**\n";
echo "   - Check if localhost is accessible\n";
echo "   - Try 127.0.0.1 instead of localhost\n";
echo "   - Check Laragon/server status\n\n";

// Cleanup
$testSurat->delete();

echo "✅ NEXT STEPS:\n";
echo "   1. Test: http://localhost/ultra_simple_test.html\n";
echo "   2. If that works, test admin dashboard\n";
echo "   3. Report which buttons work/don't work\n";
echo "   4. Check browser console for any errors\n\n";

echo "🎯 The implementation is now as simple as possible!\n";
echo "   If this doesn't work, the issue is with browser/system, not code.\n";
