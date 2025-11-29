<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Minimal bootstrap seeder for production/server deployments.
 * Seeds only critical data: Admin account and application Settings.
 */
class StartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $this->call(AdminSeeder::class);

        // Core app settings
        $this->call(SettingsSeeder::class);
    }
}
