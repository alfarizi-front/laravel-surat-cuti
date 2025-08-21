<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CapStempelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample cap/stempel for admin/kadin users
        $adminUsers = User::where('role', 'admin')
                         ->orWhere('role', 'kadin')
                         ->orWhere('jabatan', 'like', '%Kepala%')
                         ->get();

        foreach ($adminUsers as $user) {
            // Create a simple text-based cap/stempel image
            $this->createSampleCap($user);
        }

        $this->command->info('Sample cap/stempel created for admin users.');
    }

    private function createSampleCap($user)
    {
        // Create a simple SVG cap/stempel
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="100" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <style>
      .text { font-family: Arial, sans-serif; font-weight: bold; text-anchor: middle; }
      .title { font-size: 12px; }
      .subtitle { font-size: 10px; }
      .name { font-size: 8px; }
    </style>
  </defs>
  
  <!-- Outer circle -->
  <circle cx="100" cy="50" r="45" fill="none" stroke="#000" stroke-width="2"/>
  
  <!-- Inner circle -->
  <circle cx="100" cy="50" r="35" fill="none" stroke="#000" stroke-width="1"/>
  
  <!-- Text -->
  <text x="100" y="25" class="text title">DINAS KESEHATAN</text>
  <text x="100" y="40" class="text subtitle">KABUPATEN PURWOREJO</text>
  <text x="100" y="55" class="text name">' . strtoupper($user->nama) . '</text>
  <text x="100" y="70" class="text name">' . strtoupper($user->jabatan) . '</text>
</svg>';

        // Convert SVG to PNG (simplified - in real implementation you might want to use a proper image library)
        $filename = 'cap-stempel/cap_' . $user->id . '_sample.svg';
        
        // Store the SVG file
        Storage::disk('public')->put($filename, $svg);
        
        // Update user record
        $user->update([
            'cap_stempel' => $filename,
            'gunakan_cap' => true
        ]);
    }
}
