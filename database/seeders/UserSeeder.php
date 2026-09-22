<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Human In Motion Admin', 'email' => 'admin@humaninmotion.co.uk', 'role' => 'super_admin'],
            ['name' => 'Store Manager', 'email' => 'manager@humaninmotion.co.uk', 'role' => 'admin'],
            ['name' => 'Content Team', 'email' => 'content@humaninmotion.co.uk', 'role' => 'content_manager'],
            ['name' => 'Customer Support', 'email' => 'support@humaninmotion.co.uk', 'role' => 'support'],
            ['name' => 'Alex Carter', 'email' => 'alex@example.com', 'role' => 'customer'],
            ['name' => 'Jamie Patel', 'email' => 'jamie@example.com', 'role' => 'customer'],
            ['name' => 'Ravi Singh', 'email' => 'ravi@example.com', 'role' => 'customer'],
            ['name' => 'Tom Okafor', 'email' => 'tom@example.com', 'role' => 'customer'],
        ];

        foreach ($users as $index => $user) {
            User::updateOrCreate(['email' => $user['email']], [
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => '+44 7700 900' . str_pad((string) $index, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role' => $user['role'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}