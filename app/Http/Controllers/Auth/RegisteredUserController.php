<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    private function getUnitKerja(): array
    {
        return [
            'Puskesmas' => 'Puskesmas',
            'Sekretariat' => 'Sekretariat',
            'Bidang' => 'Bidang',
        ];
    }

    private function getJabatanOptions(): array
    {
        return [
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
    }

    public function create(): View
    {
        $allUnitKerja = $this->getUnitKerja();

        $jabatanOptions = $this->getJabatanOptions();

        return view('auth.register', compact('allUnitKerja', 'jabatanOptions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:18', 'min:18', 'unique:'.User::class, 'regex:/^\d{18}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jabatan' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255', 'in:'.implode(',', array_keys($this->getUnitKerja()))],
            'jenis_pegawai' => ['required', 'in:ASN,PPPK'],
        ]);

        // Validasi jabatan sesuai unit kerja
        $jabatanValid = false;
        $unitKerja = $request->unit_kerja;
        $jabatan = $request->jabatan;
        $jabatanOptions = $this->getJabatanOptions();

        if (str_contains($unitKerja, 'Puskesmas')) {
            $jabatanValid = isset($jabatanOptions['puskesmas'][$jabatan]);
        } elseif ($unitKerja === 'Sekretariat') {
            $jabatanValid = isset($jabatanOptions['sekretariat'][$jabatan]);
        } elseif (str_contains($unitKerja, 'Bidang')) {
            $jabatanValid = isset($jabatanOptions['bidang'][$jabatan]);
        }

        if (! $jabatanValid) {
            throw ValidationException::withMessages([
                'jabatan' => ['Jabatan tidak valid untuk unit kerja yang dipilih'],
            ]);
        }

        $user = User::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => 'karyawan', // Default role
            'jenis_pegawai' => $request->jenis_pegawai,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
