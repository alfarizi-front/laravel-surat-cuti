<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Main seeder that orchestrates all database seeding operations.
 * Runs seeders in the correct order to maintain referential integrity.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Core data (no dependencies)
        $this->call([
            JenisCutiSeeder::class,
            AlurCutiSeeder::class,
            PuskesmasSeeder::class,
        ]);

        // User data
        $this->call([
            UserSeeder::class,
            SekretariatUserSeeder::class,
        ]);

        // Dependent data
        $this->call([
            CutiTahunanSeeder::class,
            CapStempelSeeder::class,
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
    }
}
