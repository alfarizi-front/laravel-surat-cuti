<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            [
                'nama' => 'Administrator',
                'email' => 'admin@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Administrator',
                'unit_kerja' => 'Sekretariat',
                'role' => 'admin',
                'jenis_pegawai' => 'ASN'
            ],

            // KADIN
            [
                'nama' => 'Dr. Kepala Dinas',
                'email' => 'kadin@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'KADIN',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kadin',
                'jenis_pegawai' => 'ASN',
                'nip' => '196501011990031001',
                'tanda_tangan' => 'signatures/kadin.png'
            ],

            // Sekretaris Dinas
            [
                'nama' => 'Sekretaris Dinas',
                'email' => 'sekdin@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Sekretaris Dinas',
                'unit_kerja' => 'Sekretariat',
                'role' => 'sekdin',
                'jenis_pegawai' => 'ASN',
                'nip' => '196801011991031002',
                'tanda_tangan' => 'signatures/sekdin.png'
            ],

            // Kasubag Umpeg
            [
                'nama' => 'Kasubag Umpeg',
                'email' => 'umpeg@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasubag Umpeg',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kasubag',
                'jenis_pegawai' => 'ASN'
            ],

            // Kepala Puskesmas
            [
                'nama' => 'Dr. Kepala Puskesmas',
                'email' => 'kapus@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kepala Puskesmas',
                'unit_kerja' => 'Puskesmas',
                'role' => 'kepala',
                'jenis_pegawai' => 'ASN',
                'nip' => '197001011992031003',
                'tanda_tangan' => 'signatures/kapus.png'
            ],

            // Kepala Tata Usaha
            [
                'nama' => 'Kepala Tata Usaha',
                'email' => 'katu@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kepala Tata Usaha',
                'unit_kerja' => 'Puskesmas',
                'role' => 'kepala',
                'jenis_pegawai' => 'ASN'
            ],

            // Kepala Bidang
            [
                'nama' => 'Kepala Bidang',
                'email' => 'kabid@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kepala Bidang',
                'unit_kerja' => 'Bidang',
                'role' => 'kepala',
                'jenis_pegawai' => 'ASN',
                'nip' => '197201011993031004',
                'tanda_tangan' => 'signatures/kabid.png'
            ],

            // Karyawan ASN
            [
                'nama' => 'Karyawan ASN',
                'email' => 'asn@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff',
                'unit_kerja' => 'Puskesmas',
                'role' => 'karyawan',
                'jenis_pegawai' => 'ASN'
            ],

            // Karyawan PPPK
            [
                'nama' => 'Karyawan PPPK',
                'email' => 'pppk@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff',
                'unit_kerja' => 'Puskesmas',
                'role' => 'karyawan',
                'jenis_pegawai' => 'PPPK'
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Cari berdasarkan email
                $userData // Update atau create dengan data ini
            );
        }
    }
}
