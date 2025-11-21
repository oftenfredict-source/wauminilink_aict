<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TreasurerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create treasurer/accountant user
        User::updateOrCreate(
            ['email' => 'treasurer@waumini.com'],
            [
                'name' => 'Treasurer/Accountant',
                'password' => Hash::make('password'),
                'role' => 'treasurer',
                'can_approve_finances' => false,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Treasurer/Accountant user created successfully!');
        $this->command->info('Email: treasurer@waumini.com');
        $this->command->info('Password: password');
        $this->command->info('Role: treasurer (Finance access only)');
    }
}


