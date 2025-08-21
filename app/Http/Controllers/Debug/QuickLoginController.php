<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * QuickLoginController
 * 
 * Provides quick login functionality for development environment.
 * Only accessible in local environment for testing purposes.
 */
class QuickLoginController extends Controller
{
    /**
     * Available test accounts with their email mappings
     */
    private const TEST_ACCOUNTS = [
        // Admin & System
        'admin' => 'admin@dinkes.go.id',
        'kadin' => 'kadin@dinkes.go.id',
        
        // General Staff
        'asn' => 'asn@dinkes.go.id',
        'pppk' => 'pppk@dinkes.go.id',
        
        // Puskesmas
        'katu' => 'katu@dinkes.go.id',
        'kapus' => 'kapus@dinkes.go.id',
        
        // Cross-unit roles
        'umpeg' => 'umpeg@dinkes.go.id',
        'sekdin' => 'sekdin@dinkes.go.id',
        'kabid' => 'kabid@dinkes.go.id',
        
        // Sekretariat workflow
        'karyawan-sekretariat' => 'karyawan.sekretariat@dinkes.go.id',
        'kasubag-sekretariat' => 'kasubag.sekretariat@dinkes.go.id',
        'kasubag-umpeg' => 'kasubag.umpeg@dinkes.go.id',
        'kasubag-perencanaan' => 'kasubag.perencanaan@dinkes.go.id',
        'sekretaris-dinas' => 'sekretaris.dinas@dinkes.go.id',
        'kepala-dinas' => 'kepala.dinas@dinkes.go.id',
        'test-pppk-sekretariat' => 'test.pppk.sekretariat@dinkes.go.id',
    ];

    /**
     * Show quick login dashboard
     */
    public function index()
    {
        $this->ensureLocalEnvironment();

        $statistics = $this->getStatistics();

        return view('debug.quick-login', $statistics);
    }

    /**
     * Perform quick login for specified role
     */
    public function login(string $role)
    {
        $this->ensureLocalEnvironment();

        if (!isset(self::TEST_ACCOUNTS[$role])) {
            return $this->redirectWithError('Role tidak ditemukan: ' . $role);
        }

        $email = self::TEST_ACCOUNTS[$role];
        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->redirectWithError('User tidak ditemukan untuk email: ' . $email);
        }

        $this->performLogin($user);

        $roleInfo = $this->getRoleInfo($user);
        
        return redirect()->route('dashboard')
                       ->with('success', "Quick login berhasil sebagai {$user->nama} ({$roleInfo})");
    }

    /**
     * Ensure this controller only works in local environment
     */
    private function ensureLocalEnvironment(): void
    {
        if (!app()->environment('local')) {
            abort(404);
        }
    }

    /**
     * Get statistics for the dashboard
     */
    private function getStatistics(): array
    {
        return [
            'totalAccounts' => User::count(),
            'workflows' => 3, // Sekretariat, Puskesmas, Bidang
            'roles' => User::distinct('role')->count('role'),
            'features' => 4, // New features implemented
        ];
    }

    /**
     * Redirect with error message
     */
    private function redirectWithError(string $message)
    {
        return redirect()->route('debug.quick-login')->with('error', $message);
    }

    /**
     * Perform the actual login
     */
    private function performLogin(User $user): void
    {
        // Logout current user if any
        if (Auth::check()) {
            Auth::logout();
        }

        // Login as the selected user
        Auth::login($user);
    }

    /**
     * Get formatted role information for display
     */
    private function getRoleInfo(User $user): string
    {
        $roleMap = [
            'admin' => 'System Administrator',
            'kadin' => 'Kepala Dinas',
            'sekdin' => 'Sekretaris Dinas', 
            'kasubag' => 'Kepala Sub Bagian',
            'kepala' => 'Kepala Unit',
            'karyawan' => $user->jenis_pegawai ?? 'Karyawan'
        ];

        $baseRole = $roleMap[$user->role] ?? ucfirst($user->role);
        
        if ($user->unit_kerja && $user->unit_kerja !== 'Sekretariat') {
            return "{$baseRole} - {$user->unit_kerja}";
        }
        
        return $baseRole;
    }
}