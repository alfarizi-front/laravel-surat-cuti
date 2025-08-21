<?php

namespace Database\Seeders;

use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SisaCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users except admin
        $users = User::where('role', '!=', 'admin')->get();

        $tahunArray = [2023, 2024, 2025];

        foreach ($users as $user) {
            foreach ($tahunArray as $tahun) {
                // Default sisa cuti berdasarkan tahun
                $sisaAwal = 12; // Default 12 hari per tahun
                $cutiDiambil = 0;

                // Simulasi data untuk tahun sebelumnya
                if ($tahun == 2023) {
                    $cutiDiambil = rand(0, 8); // Random 0-8 hari sudah diambil
                } elseif ($tahun == 2024) {
                    $cutiDiambil = rand(0, 6); // Random 0-6 hari sudah diambil
                } else { // 2025
                    $cutiDiambil = 0; // Tahun baru, belum ada yang diambil
                }

                $sisaAkhir = $sisaAwal - $cutiDiambil;

                SisaCuti::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tahun' => $tahun
                    ],
                    [
                        'sisa_awal' => $sisaAwal,
                        'cuti_diambil' => $cutiDiambil,
                        'sisa_akhir' => $sisaAkhir,
                        'keterangan' => $tahun == 2025 ? 'Sisa cuti tahun berjalan' : "Sisa cuti tahun {$tahun}",
                        'is_active' => true
                    ]
                );
            }
        }

        $this->command->info('Sisa cuti data seeded successfully for ' . $users->count() . ' users across 3 years!');

        // Show summary
        $totalRecords = SisaCuti::count();
        $this->command->info("Total sisa cuti records created: {$totalRecords}");

        // Show sample data
        $this->command->info("\nSample data:");
        $sampleUser = $users->first();
        if ($sampleUser) {
            $sisaCutiSample = SisaCuti::where('user_id', $sampleUser->id)->get();
            foreach ($sisaCutiSample as $sisa) {
                $this->command->info("  {$sampleUser->nama} - {$sisa->tahun}: {$sisa->sisa_akhir} hari tersisa");
            }
        }
    }
}
