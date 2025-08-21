<?php

namespace Database\Seeders;

use App\Models\Signature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $signatures = [
            [
                'jabatan' => 'KADIN',
                'nama' => 'dr. Sudarmi, MM',
                'nip' => '19690220 200212 2 004',
                'is_active' => true,
                'keterangan' => 'Kepala Dinas Kesehatan Daerah Kabupaten Purworejo'
            ],
            [
                'jabatan' => 'Kepala Puskesmas',
                'nama' => 'dr. Kepala Puskesmas',
                'nip' => '196506061995036006',
                'is_active' => true,
                'keterangan' => 'Kepala Puskesmas'
            ],
            [
                'jabatan' => 'SEKDIN',
                'nama' => 'Sekretaris Dinas',
                'nip' => null,
                'is_active' => true,
                'keterangan' => 'Sekretaris Dinas Kesehatan'
            ],
            [
                'jabatan' => 'Kepala Bidang',
                'nama' => 'Kepala Bidang',
                'nip' => null,
                'is_active' => true,
                'keterangan' => 'Kepala Bidang'
            ]
        ];

        foreach ($signatures as $signatureData) {
            Signature::updateOrCreate(
                ['jabatan' => $signatureData['jabatan']],
                $signatureData
            );
        }

        $this->command->info('Default signatures created successfully!');
    }
}
