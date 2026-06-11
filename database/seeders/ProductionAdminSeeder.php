<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin credentials from .env file
        $adminEmail = env('ADMIN_EMAIL', 'admin@souq.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');
        
        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin User',
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: ' . $adminEmail);
    }
}