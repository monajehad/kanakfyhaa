<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * ⚠️ هذا الـ Seeder لا يقبل التعديل - ينشئ مستخدم Admin واحد فقط
     */
    public function run(): void
    {
        $this->command->info('👤 Creating Admin user...');

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'type' => 'admin',
            ]
        );

        $this->command->info('✅ Admin user created successfully!');
    }
}