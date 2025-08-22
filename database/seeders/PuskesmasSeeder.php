<?php

namespace Database\Seeders;

use App\Models\Puskesmas;
use Illuminate\Database\Seeder;

class PuskesmasSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 27; $i++) {
            Puskesmas::create([
 
                'nama' => 'Puskesmas '.$i,
 
 
                'nama' => 'Puskesmas '.$i,
 
                'nama_puskesmas' => 'Puskesmas '.$i,
 
 
            ]);
        }
    }
}
