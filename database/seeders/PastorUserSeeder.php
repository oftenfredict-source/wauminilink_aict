<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PastorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Pastor user
        User::create([
            'name' => 'Pastor John Doe',
            'email' => 'pastor@waumini.com',
            'password' => Hash::make('password'),
            'role' => 'pastor',
            'can_approve_finances' => true,
        ]);

        // Create a Treasurer user
        User::create([
            'name' => 'Treasurer Jane Smith',
            'email' => 'treasurer@waumini.com',
            'password' => Hash::make('password'),
            'role' => 'treasurer',
            'can_approve_finances' => false,
        ]);

        // Create an Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@waumini.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'can_approve_finances' => true,
        ]);
    }
}