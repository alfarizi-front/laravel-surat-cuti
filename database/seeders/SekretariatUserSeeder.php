<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SekretariatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Karyawan Sekretariat untuk testing
            [
                'nama' => 'Karyawan Sekretariat',
                'email' => 'karyawan.sekretariat@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff Sekretariat',
                'unit_kerja' => 'Sekretariat',
                'role' => 'karyawan',
                'jenis_pegawai' => 'ASN',
                'nip' => '198001012005011001'
            ],
            
            // Kasubag Sekretariat
            [
                'nama' => 'Kasubag Sekretariat',
                'email' => 'kasubag.sekretariat@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasubag',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kasubag',
                'jenis_pegawai' => 'ASN',
                'nip' => '197501012000031001'
            ],
            
            // Kasubag Umpeg
            [
                'nama' => 'Kasubag Umpeg',
                'email' => 'kasubag.umpeg@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasubag Umpeg',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kasubag',
                'jenis_pegawai' => 'ASN',
                'nip' => '197601012001031001'
            ],
            
            // Kasubag Perencanaan Keu
            [
                'nama' => 'Kasubag Perencanaan Keu',
                'email' => 'kasubag.perencanaan@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasubag Perencanaan Keu',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kasubag',
                'jenis_pegawai' => 'ASN',
                'nip' => '197701012002031001'
            ],
            
            // Sekretaris Dinas (if not exists)
            [
                'nama' => 'Sekretaris Dinas',
                'email' => 'sekretaris.dinas@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Sekretaris Dinas',
                'unit_kerja' => 'Sekretariat',
                'role' => 'sekdin',
                'jenis_pegawai' => 'ASN',
                'nip' => '196501011990031001'
            ],
            
            // KADIN (if not exists)
            [
                'nama' => 'Kepala Dinas Kesehatan',
                'email' => 'kepala.dinas@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'KADIN',
                'unit_kerja' => 'Sekretariat',
                'role' => 'kadin',
                'jenis_pegawai' => 'ASN',
                'nip' => '196001011985031001'
            ],
            
            // Additional test users for different scenarios
            [
                'nama' => 'Test PPPK Sekretariat',
                'email' => 'test.pppk.sekretariat@dinkes.go.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff PPPK',
                'unit_kerja' => 'Sekretariat',
                'role' => 'karyawan',
                'jenis_pegawai' => 'PPPK',
                'nip' => null
            ]
        ];

        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                User::create($userData);
                echo "Created user: {$userData['nama']} ({$userData['email']})\n";
            } else {
                echo "User already exists: {$userData['email']}\n";
            }
        }
    }
}