<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'superadmin@photosys.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('SuperAdmin user created successfully!');
        $this->command->info('Email: superadmin@photosys.com');
        $this->command->info('Password: password123');
    }
}
