<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CutiTahunan;

class CutiTahunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');
        
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Create cuti tahunan record for current year if not exists
            CutiTahunan::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'tahun' => $currentYear
                ],
                [
                    'jatah_cuti' => 12,
                    'cuti_digunakan' => 0,
                    'cuti_pending' => 0,
                    'sisa_cuti' => 12
                ]
            );
        }
        
        $this->command->info('Cuti tahunan records created for all users.');
    }
}
