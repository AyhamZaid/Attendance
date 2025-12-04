<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a test trainer user
        User::firstOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Test Trainer',
                'email' => 'trainer@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create a test trainee user
        User::firstOrCreate(
            ['email' => 'trainee@example.com'],
            [
                'name' => 'Test Trainee',
                'email' => 'trainee@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Test users created:');
        $this->command->info('Trainer: trainer@example.com / password');
        $this->command->info('Trainee: trainee@example.com / password');
    }
}


