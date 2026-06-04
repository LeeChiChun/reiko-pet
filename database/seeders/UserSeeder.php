<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => '管理員',
            'email' => 'admin@petgrooming.com',
            'phone' => '02-1234-5678',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '林美容師',
            'email' => 'groomer1@petgrooming.com',
            'phone' => '0912-345-678',
            'role' => 'groomer',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '陳美容師',
            'email' => 'groomer2@petgrooming.com',
            'phone' => '0923-456-789',
            'role' => 'groomer',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '王小明',
            'email' => 'customer1@example.com',
            'phone' => '0934-567-890',
            'role' => 'customer',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '張小華',
            'email' => 'customer2@example.com',
            'phone' => '0945-678-901',
            'role' => 'customer',
            'password' => Hash::make('password'),
        ]);
    }
}
