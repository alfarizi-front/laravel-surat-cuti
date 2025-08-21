<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlurCuti;

class AlurCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alurCuti = [
            // Puskesmas
            ['unit_kerja' => 'Puskesmas', 'step_ke' => 1, 'jabatan' => 'Kepala Tata Usaha', 'tipe_disposisi' => 'paraf', 'urutan' => 1],
            ['unit_kerja' => 'Puskesmas', 'step_ke' => 2, 'jabatan' => 'Kepala Puskesmas', 'tipe_disposisi' => 'ttd', 'urutan' => 2],
            ['unit_kerja' => 'Puskesmas', 'step_ke' => 3, 'jabatan' => 'Kasubag Umpeg', 'tipe_disposisi' => 'paraf', 'urutan' => 3],
            ['unit_kerja' => 'Puskesmas', 'step_ke' => 4, 'jabatan' => 'Sekretaris Dinas', 'tipe_disposisi' => 'paraf', 'urutan' => 4],
            ['unit_kerja' => 'Puskesmas', 'step_ke' => 5, 'jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd', 'urutan' => 5],

            // Sekretariat - Updated workflow with conditional Umpeg/Perencanaan Keu
            ['unit_kerja' => 'Sekretariat', 'step_ke' => 1, 'jabatan' => 'Kasubag', 'tipe_disposisi' => 'paraf', 'urutan' => 1],
            ['unit_kerja' => 'Sekretariat', 'step_ke' => 2, 'jabatan' => 'Kasubag Umpeg', 'tipe_disposisi' => 'paraf', 'urutan' => 2],
            ['unit_kerja' => 'Sekretariat', 'step_ke' => 3, 'jabatan' => 'Kasubag Perencanaan Keu', 'tipe_disposisi' => 'paraf', 'urutan' => 3],
            ['unit_kerja' => 'Sekretariat', 'step_ke' => 4, 'jabatan' => 'Sekretaris Dinas', 'tipe_disposisi' => 'paraf', 'urutan' => 4],
            ['unit_kerja' => 'Sekretariat', 'step_ke' => 5, 'jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd', 'urutan' => 5],

            // Bidang
            ['unit_kerja' => 'Bidang', 'step_ke' => 1, 'jabatan' => 'Kepala Bidang', 'tipe_disposisi' => 'ttd', 'urutan' => 1],
            ['unit_kerja' => 'Bidang', 'step_ke' => 2, 'jabatan' => 'Kasubag Umpeg', 'tipe_disposisi' => 'paraf', 'urutan' => 2],
            ['unit_kerja' => 'Bidang', 'step_ke' => 3, 'jabatan' => 'Sekretaris Dinas', 'tipe_disposisi' => 'paraf', 'urutan' => 3],
            ['unit_kerja' => 'Bidang', 'step_ke' => 4, 'jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd', 'urutan' => 4],
        ];

        foreach ($alurCuti as $alur) {
            AlurCuti::create($alur);
        }
    }
}