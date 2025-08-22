<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        // Data unit kerja yang tersedia
        $allUnitKerja = [
            'Puskesmas' => 'Puskesmas',
            'Sekretariat' => 'Sekretariat',
            'Bidang' => 'Bidang',
        ];

        // Data jabatan berdasarkan unit kerja
        $jabatanOptions = [
            'puskesmas' => [
                'Kepala Puskesmas' => 'Kepala Puskesmas',
                'Kepala Tata Usaha' => 'Kepala Tata Usaha',
                'Dokter' => 'Dokter',
                'Dokter Gigi' => 'Dokter Gigi',
                'Perawat' => 'Perawat',
                'Bidan' => 'Bidan',
                'Tenaga Kesehatan' => 'Tenaga Kesehatan',
                'Staff Administrasi' => 'Staff Administrasi',
            ],
            'sekretariat' => [
                'Sekretaris Dinas' => 'Sekretaris Dinas',
                'Kasubag Umum dan Kepegawaian' => 'Kasubag Umum dan Kepegawaian',
                'Kasubag Program dan Keuangan' => 'Kasubag Program dan Keuangan',
                'Staff Sekretariat' => 'Staff Sekretariat',
            ],
            'bidang' => [
                'Kepala Bidang' => 'Kepala Bidang',
                'Kepala Seksi' => 'Kepala Seksi',
                'Staff Bidang' => 'Staff Bidang',
            ],
        ];

        return view('auth.register', compact('allUnitKerja', 'jabatanOptions'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:users'],
            'unit_kerja' => ['required', 'in:Puskesmas,Sekretariat,Bidang'],
            'jabatan' => ['required', 'string'],
            'jenis_pegawai' => ['required', 'in:ASN,PPPK'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tentukan role berdasarkan jabatan
        $role = $this->determineRole($request->jabatan);

        $user = User::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'jenis_pegawai' => $request->jenis_pegawai,
            'role' => $role,
        ]);

        // Auto login setelah registrasi
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Sistem Surat Cuti.');
    }

    /**
     * Determine user role based on jabatan
     */
    private function determineRole($jabatan)
    {
        $roleMapping = [
            'Kepala Dinas' => 'kadin',
            'Sekretaris Dinas' => 'sekdin',
            'Kasubag Umum dan Kepegawaian' => 'kasubag',
            'Kasubag Program dan Keuangan' => 'kasubag',
            'Kepala Puskesmas' => 'kepala',
            'Kepala Bidang' => 'kepala',
            'Kepala Seksi' => 'kepala',
            'Kepala Tata Usaha' => 'kepala',
        ];

        return $roleMapping[$jabatan] ?? 'karyawan';
    }
}
