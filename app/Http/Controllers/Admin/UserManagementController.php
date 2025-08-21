<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Data untuk form
        $unitKerjaOptions = $this->getUnitKerjaOptions();
        $jabatanOptions = $this->getJabatanOptions();
        $roleOptions = $this->getRoleOptions();

        return view('admin.users.create', compact('unitKerjaOptions', 'jabatanOptions', 'roleOptions'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jabatan' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,kadin,sekdin,kasubag,kepala,karyawan'],
            'jenis_pegawai' => ['required', 'in:ASN,PPPK'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => $request->role,
            'jenis_pegawai' => $request->jenis_pegawai,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil ditambahkan: ' . $user->nama);
    }

    /**
     * Show the form for editing user
     */
    public function edit(User $user)
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $unitKerjaOptions = $this->getUnitKerjaOptions();
        $jabatanOptions = $this->getJabatanOptions();
        $roleOptions = $this->getRoleOptions();

        return view('admin.users.edit', compact('user', 'unitKerjaOptions', 'jabatanOptions', 'roleOptions'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:users,nip,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'jabatan' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,kadin,sekdin,kasubag,kepala,karyawan'],
            'jenis_pegawai' => ['required', 'in:ASN,PPPK'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => $request->role,
            'jenis_pegawai' => $request->jenis_pegawai,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diupdate: ' . $user->nama);
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        // Check if user has related data
        $suratCutiCount = $user->suratCuti()->count();
        $disposisiCount = $user->disposisiCuti()->count();

        if ($suratCutiCount > 0 || $disposisiCount > 0) {
            return back()->with('error', 'User tidak dapat dihapus karena memiliki data surat cuti atau disposisi!');
        }

        $userName = $user->nama;
        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil dihapus: ' . $userName);
    }

    /**
     * Get unit kerja options
     */
    private function getUnitKerjaOptions()
    {
        return [
            'Sekretariat' => 'Sekretariat',
            'Bidang Kesehatan Masyarakat' => 'Bidang Kesehatan Masyarakat',
            'Bidang Pencegahan dan Pengendalian Penyakit' => 'Bidang Pencegahan dan Pengendalian Penyakit',
            'Bidang Pelayanan Kesehatan' => 'Bidang Pelayanan Kesehatan',
            'Bidang Sumber Daya Kesehatan' => 'Bidang Sumber Daya Kesehatan',
            'Puskesmas Kota' => 'Puskesmas Kota',
            'Puskesmas Utara' => 'Puskesmas Utara',
            'Puskesmas Selatan' => 'Puskesmas Selatan',
            'Puskesmas Timur' => 'Puskesmas Timur',
            'Puskesmas Barat' => 'Puskesmas Barat',
            'Puskesmas Tengah' => 'Puskesmas Tengah',
            'Puskesmas Rawat Inap' => 'Puskesmas Rawat Inap',
            'Puskesmas Induk' => 'Puskesmas Induk',
        ];
    }

    /**
     * Get jabatan options
     */
    private function getJabatanOptions()
    {
        return [
            // Pimpinan
            'Kepala Dinas' => 'Kepala Dinas',
            'Sekretaris Dinas' => 'Sekretaris Dinas',
            
            // Sekretariat
            'Kasubag Umum dan Kepegawaian' => 'Kasubag Umum dan Kepegawaian',
            'Kasubag Program dan Keuangan' => 'Kasubag Program dan Keuangan',
            'Staff Sekretariat' => 'Staff Sekretariat',
            
            // Bidang
            'Kepala Bidang' => 'Kepala Bidang',
            'Kepala Seksi' => 'Kepala Seksi',
            'Staff Bidang' => 'Staff Bidang',
            
            // Puskesmas
            'Kepala Puskesmas' => 'Kepala Puskesmas',
            'Kepala Tata Usaha' => 'Kepala Tata Usaha',
            'Dokter' => 'Dokter',
            'Dokter Gigi' => 'Dokter Gigi',
            'Perawat' => 'Perawat',
            'Bidan' => 'Bidan',
            'Tenaga Kesehatan' => 'Tenaga Kesehatan',
            'Staff Administrasi' => 'Staff Administrasi',
        ];
    }

    /**
     * Get role options with descriptions
     */
    private function getRoleOptions()
    {
        return [
            'admin' => 'Administrator - Kelola seluruh sistem',
            'kadin' => 'Kepala Dinas - Persetujuan final surat cuti',
            'sekdin' => 'Sekretaris Dinas - Disposisi surat cuti bidang',
            'kasubag' => 'Kasubag Umpeg - Disposisi surat cuti sekretariat',
            'kepala' => 'Kepala Unit - Disposisi surat cuti unit kerja',
            'karyawan' => 'Karyawan - Mengajukan surat cuti',
        ];
    }
}
