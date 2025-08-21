<?php

namespace App\Http\Controllers;

use App\Models\SuratCuti;
use App\Models\DisposisiCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Constructor middleware sudah dihandle di routes, tidak perlu di sini

    public function index()
    {
        $user = Auth::user();
        $data = [];

        try {
            switch ($user->role) {
                case 'admin':
                    $data = $this->getAdminDashboard();
                    break;
                case 'karyawan':
                    $data = $this->getKaryawanDashboard($user);
                    break;
                default:
                    $data = $this->getDisposisiDashboard($user);
                    break;
            }
        } catch (\Exception $e) {
            // If there's an error, provide default data
            \Log::error('Dashboard error: ' . $e->getMessage());
            $data = [
                'total_surat' => 0,
                'surat_pending' => 0,
                'surat_disetujui' => 0,
                'surat_ditolak' => 0,
                'recent_surat' => collect([])
            ];
        }

        return view('dashboard', compact('data'));
    }

    private function getAdminDashboard()
    {
        return [
            'total_surat' => SuratCuti::count(),
            'surat_pending' => SuratCuti::where('status', 'proses')->count(),
            'surat_disetujui' => SuratCuti::where('status', 'disetujui')->count(),
            'surat_ditolak' => SuratCuti::where('status', 'ditolak')->count(),
            'recent_surat' => SuratCuti::with(['pengaju', 'jenisCuti'])
                                      ->latest()
                                      ->limit(5)
                                      ->get()
        ];
    }

    private function getKaryawanDashboard($user)
    {
        return [
            'total_surat' => SuratCuti::where('pengaju_id', $user->id)->count(),
            'surat_draft' => SuratCuti::where('pengaju_id', $user->id)
                                     ->where('status', 'draft')
                                     ->count(),
            'surat_proses' => SuratCuti::where('pengaju_id', $user->id)
                                      ->where('status', 'proses')
                                      ->count(),
            'surat_disetujui' => SuratCuti::where('pengaju_id', $user->id)
                                         ->where('status', 'disetujui')
                                         ->count(),
            'recent_surat' => SuratCuti::where('pengaju_id', $user->id)
                                      ->with(['jenisCuti'])
                                      ->latest()
                                      ->limit(5)
                                      ->get()
        ];
    }

    private function getDisposisiDashboard($user)
    {
        return [
            'pending_disposisi' => DisposisiCuti::where('user_id', $user->id)
                                               ->where('status', 'pending')
                                               ->count(),
            'completed_disposisi' => DisposisiCuti::where('user_id', $user->id)
                                                 ->where('status', 'sudah')
                                                 ->count(),
            'recent_disposisi' => DisposisiCuti::where('user_id', $user->id)
                                              ->with(['suratCuti.pengaju', 'suratCuti.jenisCuti'])
                                              ->latest()
                                              ->limit(5)
                                              ->get()
        ];
    }
}
