<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SuratCuti;
use App\Models\CutiTahunan;

class SystemDebugCommand extends Command
{
    protected $signature = 'debug:system {--fix : Attempt to fix common issues}';
    protected $description = 'Debug system issues and optionally fix them';

    public function handle()
    {
        $this->info('🔍 Starting System Debug...');
        $this->newLine();

        // 1. Check Routes
        $this->checkRoutes();
        
        // 2. Check Database
        $this->checkDatabase();
        
        // 3. Check Files
        $this->checkFiles();
        
        // 4. Check Permissions
        $this->checkPermissions();
        
        // 5. Check Configuration
        $this->checkConfiguration();
        
        // 6. Fix issues if requested
        if ($this->option('fix')) {
            $this->fixCommonIssues();
        }
        
        $this->newLine();
        $this->info('✅ System Debug Complete!');
        $this->info('🌐 Visit /debug/view for detailed web interface');
    }

    private function checkRoutes()
    {
        $this->info('📍 Checking Routes...');
        
        $routesToCheck = [
            'admin.laporan.index',
            'admin.laporan.export',
            'admin.laporan.print',
            'admin.laporan.chart-data',
            'surat-cuti.index',
            'surat-cuti.create',
            'dashboard',
            'signature.upload',
            'signature.store'
        ];
        
        foreach ($routesToCheck as $routeName) {
            try {
                $url = route($routeName);
                $this->line("  ✅ {$routeName} -> {$url}");
            } catch (\Exception $e) {
                $this->error("  ❌ {$routeName} -> ERROR: {$e->getMessage()}");
            }
        }
        
        $this->newLine();
    }

    private function checkDatabase()
    {
        $this->info('🗄️ Checking Database...');
        
        try {
            DB::connection()->getPdo();
            $this->line('  ✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->error("  ❌ Database connection: ERROR - {$e->getMessage()}");
            return;
        }
        
        $tablesToCheck = [
            'users' => User::class,
            'surat_cuti' => SuratCuti::class,
            'cuti_tahunan' => CutiTahunan::class
        ];
        
        foreach ($tablesToCheck as $table => $model) {
            try {
                $count = $model::count();
                $this->line("  ✅ {$table}: {$count} records");
            } catch (\Exception $e) {
                $this->error("  ❌ {$table}: ERROR - {$e->getMessage()}");
            }
        }
        
        $this->newLine();
    }

    private function checkFiles()
    {
        $this->info('📁 Checking Files...');
        
        $filesToCheck = [
            'Controller Laporan' => app_path('Http/Controllers/Admin/LaporanController.php'),
            'Controller Surat Cuti' => app_path('Http/Controllers/SuratCutiController.php'),
            'Model CutiTahunan' => app_path('Models/CutiTahunan.php'),
            'View Laporan' => resource_path('views/admin/laporan/index.blade.php'),
            'View Create' => resource_path('views/surat-cuti/create.blade.php')
        ];
        
        foreach ($filesToCheck as $name => $path) {
            if (file_exists($path)) {
                $this->line("  ✅ {$name}: EXISTS");
            } else {
                $this->error("  ❌ {$name}: MISSING - {$path}");
            }
        }
        
        $this->newLine();
    }

    private function checkPermissions()
    {
        $this->info('🔐 Checking Permissions...');
        
        $pathsToCheck = [
            'storage/app' => storage_path('app'),
            'storage/logs' => storage_path('logs'),
            'public/storage' => public_path('storage'),
            'bootstrap/cache' => base_path('bootstrap/cache')
        ];
        
        foreach ($pathsToCheck as $name => $path) {
            if (is_dir($path)) {
                $writable = is_writable($path);
                if ($writable) {
                    $this->line("  ✅ {$name}: WRITABLE");
                } else {
                    $this->error("  ❌ {$name}: NOT WRITABLE");
                }
            } else {
                $this->error("  ❌ {$name}: DIRECTORY NOT EXISTS");
            }
        }
        
        $this->newLine();
    }

    private function checkConfiguration()
    {
        $this->info('⚙️ Checking Configuration...');
        
        $configs = [
            'APP_ENV' => config('app.env'),
            'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
            'DB_CONNECTION' => config('database.default'),
            'CACHE_DRIVER' => config('cache.default'),
            'SESSION_DRIVER' => config('session.driver')
        ];
        
        foreach ($configs as $key => $value) {
            $this->line("  ✅ {$key}: {$value}");
        }
        
        $this->newLine();
    }

    private function fixCommonIssues()
    {
        $this->info('🔧 Attempting to fix common issues...');
        
        // 1. Clear all caches
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->line('  ✅ Cleared all caches');
        
        // 2. Create storage link if missing
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
            $this->line('  ✅ Created storage link');
        }
        
        // 3. Run migrations if needed
        try {
            $this->call('migrate', ['--force' => true]);
            $this->line('  ✅ Ran migrations');
        } catch (\Exception $e) {
            $this->error("  ❌ Migration error: {$e->getMessage()}");
        }
        
        // 4. Seed cuti tahunan if table is empty
        try {
            if (CutiTahunan::count() === 0) {
                $this->call('db:seed', ['--class' => 'CutiTahunanSeeder']);
                $this->line('  ✅ Seeded cuti tahunan data');
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Seeding error: {$e->getMessage()}");
        }
        
        $this->newLine();
    }
}
