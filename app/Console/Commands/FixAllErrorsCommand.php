<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class FixAllErrorsCommand extends Command
{
    protected $signature = 'fix:all {--force : Force fix without confirmation}';
    protected $description = 'Fix all common system errors automatically';

    public function handle()
    {
        $this->info('🔧 Starting comprehensive system fix...');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('This will clear caches, run migrations, and fix common issues. Continue?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $this->fixCaches();
        $this->fixDatabase();
        $this->fixStorage();
        $this->fixDirectories();
        $this->fixPermissions();
        $this->fixRoutes();
        $this->fixViews();
        $this->fixAssets();
        $this->testSystem();

        $this->newLine();
        $this->info('✅ All fixes completed!');
        $this->info('🌐 Test your application at: http://localhost:8000');
    }

    private function fixCaches()
    {
        $this->info('🧹 Clearing all caches...');
        
        try {
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            $this->line('  ✅ All caches cleared');
        } catch (\Exception $e) {
            $this->error("  ❌ Cache error: {$e->getMessage()}");
        }
    }

    private function fixDatabase()
    {
        $this->info('🗄️ Fixing database issues...');
        
        try {
            // Test connection
            DB::connection()->getPdo();
            $this->line('  ✅ Database connection OK');
            
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            $this->line('  ✅ Migrations completed');
            
            // Check critical tables
            $tables = ['users', 'surat_cuti', 'cuti_tahunan'];
            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $this->line("  ✅ Table {$table}: {$count} records");
            }
            
        } catch (\Exception $e) {
            $this->error("  ❌ Database error: {$e->getMessage()}");
        }
    }

    private function fixStorage()
    {
        $this->info('📁 Fixing storage issues...');
        
        try {
            // Create storage link
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
                $this->line('  ✅ Storage link created');
            } else {
                $this->line('  ✅ Storage link exists');
            }
            
            // Test storage writable
            $testFile = storage_path('app/test.txt');
            file_put_contents($testFile, 'test');
            if (file_exists($testFile)) {
                unlink($testFile);
                $this->line('  ✅ Storage is writable');
            }
            
        } catch (\Exception $e) {
            $this->error("  ❌ Storage error: {$e->getMessage()}");
        }
    }

    private function fixDirectories()
    {
        $this->info('📂 Creating required directories...');
        
        $directories = [
            storage_path('app/public/signatures'),
            storage_path('app/public/cap-stempel'),
            storage_path('logs'),
            public_path('build'),
            base_path('bootstrap/cache')
        ];
        
        foreach ($directories as $dir) {
            try {
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                    $this->line("  ✅ Created: {$dir}");
                } else {
                    $this->line("  ✅ Exists: " . basename($dir));
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Directory error: {$e->getMessage()}");
            }
        }
    }

    private function fixPermissions()
    {
        $this->info('🔐 Checking permissions...');
        
        $paths = [
            storage_path('app') => 'Storage app',
            storage_path('logs') => 'Storage logs',
            base_path('bootstrap/cache') => 'Bootstrap cache'
        ];
        
        foreach ($paths as $path => $name) {
            if (is_writable($path)) {
                $this->line("  ✅ {$name}: Writable");
            } else {
                $this->error("  ❌ {$name}: Not writable");
            }
        }
    }

    private function fixRoutes()
    {
        $this->info('🛣️ Testing routes...');
        
        $routes = [
            'dashboard',
            'signature.upload',
            'signature.store',
            'surat-cuti.index'
        ];
        
        foreach ($routes as $routeName) {
            try {
                $url = route($routeName);
                $this->line("  ✅ {$routeName}: {$url}");
            } catch (\Exception $e) {
                $this->error("  ❌ {$routeName}: {$e->getMessage()}");
            }
        }
    }

    private function fixViews()
    {
        $this->info('👁️ Testing views...');
        
        $views = [
            'signature.upload',
            'surat-cuti.create',
            'dashboard'
        ];
        
        foreach ($views as $view) {
            try {
                view($view);
                $this->line("  ✅ {$view}: OK");
            } catch (\Exception $e) {
                $this->error("  ❌ {$view}: {$e->getMessage()}");
            }
        }
    }

    private function fixAssets()
    {
        $this->info('🎨 Checking assets...');
        
        try {
            if (file_exists(public_path('build/manifest.json'))) {
                $this->line('  ✅ Vite manifest exists');
            } else {
                $this->error('  ❌ Vite manifest missing - run: npm run build');
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Assets error: {$e->getMessage()}");
        }
    }

    private function testSystem()
    {
        $this->info('🧪 Testing system functionality...');
        
        try {
            // Test user model
            $userCount = User::count();
            $this->line("  ✅ Users: {$userCount} found");
            
            // Test admin user
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $this->line("  ✅ Admin user: {$admin->nama}");
                $this->line("  ✅ Signature: " . ($admin->tanda_tangan ? 'Yes' : 'No'));
                $this->line("  ✅ Cap stempel: " . ($admin->cap_stempel ? 'Yes' : 'No'));
            } else {
                $this->error('  ❌ No admin user found');
            }
            
        } catch (\Exception $e) {
            $this->error("  ❌ System test error: {$e->getMessage()}");
        }
    }
}
