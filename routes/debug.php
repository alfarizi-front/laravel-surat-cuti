<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

// Debug routes untuk testing (Only enabled in local environment)
if (app()->environment('local')) {
    Route::get('/debug/all', function () {
        $results = [];
        
        // 1. Test Database Connection
        try {
            DB::connection()->getPdo();
            $results['database'] = '✅ OK';
        } catch (\Exception $e) {
            $results['database'] = '❌ ERROR: ' . $e->getMessage();
        }
        
        // 2. Test Routes
        $routesToTest = [
            'dashboard',
            'signature.upload',
            'signature.store',
            'surat-cuti.index',
            'surat-cuti.create'
        ];
        
        foreach ($routesToTest as $routeName) {
            try {
                $url = route($routeName);
                $results['routes'][$routeName] = '✅ ' . $url;
            } catch (\Exception $e) {
                $results['routes'][$routeName] = '❌ ERROR: ' . $e->getMessage();
            }
        }
        
        // 3. Test Models
        $modelsToTest = [
            'User' => User::class,
            'SuratCuti' => \App\Models\SuratCuti::class,
            'CutiTahunan' => \App\Models\CutiTahunan::class
        ];
        
        foreach ($modelsToTest as $name => $model) {
            try {
                $count = $model::count();
                $results['models'][$name] = "✅ {$count} records";
            } catch (\Exception $e) {
                $results['models'][$name] = '❌ ERROR: ' . $e->getMessage();
            }
        }
        
        // 4. Test Storage
        try {
            $writable = is_writable(storage_path('app'));
            $results['storage']['app'] = $writable ? '✅ Writable' : '❌ Not writable';
            
            $publicWritable = is_writable(storage_path('app/public'));
            $results['storage']['public'] = $publicWritable ? '✅ Writable' : '❌ Not writable';
            
            $linkExists = file_exists(public_path('storage'));
            $results['storage']['link'] = $linkExists ? '✅ Link exists' : '❌ Link missing';
        } catch (\Exception $e) {
            $results['storage'] = '❌ ERROR: ' . $e->getMessage();
        }
        
        // 5. Test Controllers
        $controllersToTest = [
            'SignatureController' => \App\Http\Controllers\SignatureController::class,
            'SuratCutiController' => \App\Http\Controllers\SuratCutiController::class,
            'DashboardController' => \App\Http\Controllers\DashboardController::class
        ];
        
        foreach ($controllersToTest as $name => $controller) {
            $results['controllers'][$name] = class_exists($controller) ? '✅ Exists' : '❌ Missing';
        }
        
        // 6. Test Views
        $viewsToTest = [
            'signature.upload',
            'surat-cuti.create',
            'surat-cuti.index',
            'dashboard'
        ];
        
        foreach ($viewsToTest as $view) {
            try {
                view($view);
                $results['views'][$view] = '✅ Exists';
            } catch (\Exception $e) {
                $results['views'][$view] = '❌ ERROR: ' . $e->getMessage();
            }
        }
        
        // 7. Test User with Cap Stempel
        try {
            $adminUser = User::where('role', 'admin')->first();
            if ($adminUser) {
                $results['cap_stempel']['admin_user'] = '✅ Admin user exists';
                $results['cap_stempel']['has_signature'] = $adminUser->tanda_tangan ? '✅ Has signature' : '❌ No signature';
                $results['cap_stempel']['has_cap'] = $adminUser->cap_stempel ? '✅ Has cap' : '❌ No cap';
                $results['cap_stempel']['cap_active'] = $adminUser->gunakan_cap ? '✅ Cap active' : '❌ Cap inactive';
            } else {
                $results['cap_stempel'] = '❌ No admin user found';
            }
        } catch (\Exception $e) {
            $results['cap_stempel'] = '❌ ERROR: ' . $e->getMessage();
        }
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    });

    // Quick fix route
    Route::get('/debug/fix', function () {
        $fixes = [];
        
        try {
            // 1. Clear all caches
            \Artisan::call('route:clear');
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            $fixes[] = '✅ Cleared all caches';
            
            // 2. Create storage link if missing
            if (!file_exists(public_path('storage'))) {
                \Artisan::call('storage:link');
                $fixes[] = '✅ Created storage link';
            }
            
            // 3. Run migrations
            \Artisan::call('migrate', ['--force' => true]);
            $fixes[] = '✅ Ran migrations';
            
            // 4. Create directories if missing
            $dirs = [
                storage_path('app/public/signatures'),
                storage_path('app/public/cap-stempel')
            ];
            
            foreach ($dirs as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                    $fixes[] = "✅ Created directory: {$dir}";
                }
            }
            
        } catch (\Exception $e) {
            $fixes[] = '❌ ERROR: ' . $e->getMessage();
        }
        
        return response()->json(['fixes' => $fixes], 200, [], JSON_PRETTY_PRINT);
    });

    // Test specific signature functionality
    Route::get('/debug/signature', function () {
        $results = [];
        
        try {
            // Test signature controller methods
            $controller = new \App\Http\Controllers\SignatureController();
            $results['controller'] = '✅ SignatureController instantiated';
            
            // Test user with signature
            $user = auth()->user();
            if ($user) {
                $results['user'] = '✅ User logged in: ' . $user->nama;
                $results['signature'] = $user->tanda_tangan ? '✅ Has signature' : '❌ No signature';
                $results['cap'] = $user->cap_stempel ? '✅ Has cap' : '❌ No cap';
                $results['cap_active'] = $user->gunakan_cap ? '✅ Cap active' : '❌ Cap inactive';
            } else {
                $results['user'] = '❌ No user logged in';
            }
            
            // Test storage directories
            $dirs = [
                'signatures' => storage_path('app/public/signatures'),
                'cap-stempel' => storage_path('app/public/cap-stempel')
            ];
            
            foreach ($dirs as $name => $dir) {
                $results['storage'][$name] = is_dir($dir) ? '✅ Directory exists' : '❌ Directory missing';
            }
            
        } catch (\Exception $e) {
            $results['error'] = '❌ ERROR: ' . $e->getMessage();
        }
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    });
}
