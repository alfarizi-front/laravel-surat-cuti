<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisCuti;

class JenisCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisCuti = [
            ['nama' => 'Cuti Tahunan', 'berlaku_untuk' => 'Keduanya'],
            ['nama' => 'Cuti Besar', 'berlaku_untuk' => 'ASN'],
            ['nama' => 'Cuti Sakit', 'berlaku_untuk' => 'ASN'],
            ['nama' => 'Cuti Melahirkan', 'berlaku_untuk' => 'ASN'],
            ['nama' => 'Cuti Karena Alasan Penting', 'berlaku_untuk' => 'ASN'],
            ['nama' => 'Cuti Bersama', 'berlaku_untuk' => 'Keduanya'],
            ['nama' => 'Cuti Alasan Lain-lain', 'berlaku_untuk' => 'ASN'],
        ];

        foreach ($jenisCuti as $jenis) {
            JenisCuti::create($jenis);
        }
    }
}
