<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AtasanSetup;
use App\Models\User;

class AtasanSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,kadin']);
    }

    /**
     * Display atasan setup management
     */
    public function index()
    {
        $setupASN = AtasanSetup::where('jenis_pegawai', 'ASN')->orderBy('urutan_disposisi')->get();
        $setupPPPK = AtasanSetup::where('jenis_pegawai', 'PPPK')->orderBy('urutan_disposisi')->get();
        
        $availableRoles = ['sekdin', 'kadin', 'kepala', 'kasubag'];
        $availableUsers = User::whereIn('role', $availableRoles)->get();

        return view('admin.atasan-setup.index', compact('setupASN', 'setupPPPK', 'availableUsers'));
    }

    /**
     * Store new atasan setup
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pegawai' => 'required|in:ASN,PPPK',
            'level_atasan' => 'required|string',
            'nama_jabatan' => 'required|string',
            'perlu_tanda_tangan' => 'boolean',
            'perlu_cap_stempel' => 'boolean',
            'urutan_disposisi' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        AtasanSetup::create([
            'jenis_pegawai' => $request->jenis_pegawai,
            'level_atasan' => $request->level_atasan,
            'nama_jabatan' => $request->nama_jabatan,
            'perlu_tanda_tangan' => $request->has('perlu_tanda_tangan'),
            'perlu_cap_stempel' => $request->has('perlu_cap_stempel'),
            'urutan_disposisi' => $request->urutan_disposisi,
            'keterangan' => $request->keterangan,
            'aktif' => true
        ]);

        return redirect()->route('admin.atasan-setup.index')
                        ->with('success', 'Setup atasan berhasil ditambahkan!');
    }

    /**
     * Update atasan setup
     */
    public function update(Request $request, AtasanSetup $atasanSetup)
    {
        $request->validate([
            'level_atasan' => 'required|string',
            'nama_jabatan' => 'required|string',
            'perlu_tanda_tangan' => 'boolean',
            'perlu_cap_stempel' => 'boolean',
            'urutan_disposisi' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $atasanSetup->update([
            'level_atasan' => $request->level_atasan,
            'nama_jabatan' => $request->nama_jabatan,
            'perlu_tanda_tangan' => $request->has('perlu_tanda_tangan'),
            'perlu_cap_stempel' => $request->has('perlu_cap_stempel'),
            'urutan_disposisi' => $request->urutan_disposisi,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.atasan-setup.index')
                        ->with('success', 'Setup atasan berhasil diupdate!');
    }

    /**
     * Toggle active status
     */
    public function toggle(AtasanSetup $atasanSetup)
    {
        $atasanSetup->update(['aktif' => !$atasanSetup->aktif]);
        
        $status = $atasanSetup->aktif ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.atasan-setup.index')
                        ->with('success', "Setup atasan berhasil {$status}!");
    }

    /**
     * Delete atasan setup
     */
    public function destroy(AtasanSetup $atasanSetup)
    {
        $atasanSetup->delete();
        
        return redirect()->route('admin.atasan-setup.index')
                        ->with('success', 'Setup atasan berhasil dihapus!');
    }

    /**
     * Get users for specific role
     */
    public function getUsersByRole(Request $request)
    {
        $role = $request->get('role');
        
        $users = User::where('role', $role)->get(['id', 'nama', 'jabatan', 'tanda_tangan']);
        
        return response()->json($users);
    }

    /**
     * Preview template for jenis pegawai
     */
    public function previewTemplate($jenisPegawai)
    {
        $setup = AtasanSetup::getAtasanForJenisPegawai($jenisPegawai);
        
        return view('admin.atasan-setup.preview', compact('setup', 'jenisPegawai'));
    }

    /**
     * Reset to default setup
     */
    public function resetDefault(Request $request)
    {
        $jenisPegawai = $request->get('jenis_pegawai');
        
        // Delete existing setup
        AtasanSetup::where('jenis_pegawai', $jenisPegawai)->delete();
        
        // Create default setup
        if ($jenisPegawai === 'ASN') {
            AtasanSetup::create([
                'jenis_pegawai' => 'ASN',
                'level_atasan' => 'sekdin',
                'nama_jabatan' => 'Sekretaris Dinas',
                'perlu_tanda_tangan' => true,
                'perlu_cap_stempel' => false,
                'urutan_disposisi' => 1,
                'keterangan' => 'Atasan langsung untuk ASN'
            ]);
            
            AtasanSetup::create([
                'jenis_pegawai' => 'ASN',
                'level_atasan' => 'kadin',
                'nama_jabatan' => 'Kepala Dinas',
                'perlu_tanda_tangan' => true,
                'perlu_cap_stempel' => true,
                'urutan_disposisi' => 2,
                'keterangan' => 'Pimpinan tertinggi untuk ASN'
            ]);
        } else {
            AtasanSetup::create([
                'jenis_pegawai' => 'PPPK',
                'level_atasan' => 'kadin',
                'nama_jabatan' => 'Kepala Dinas',
                'perlu_tanda_tangan' => true,
                'perlu_cap_stempel' => true,
                'urutan_disposisi' => 1,
                'keterangan' => 'Atasan langsung untuk PPPK'
            ]);
        }
        
        return redirect()->route('admin.atasan-setup.index')
                        ->with('success', "Setup default untuk {$jenisPegawai} berhasil dibuat!");
    }
}
