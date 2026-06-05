<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Sophia',
            'email' => 'admin@sophia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Director
        User::create([
            'name' => 'Directeur Sophia',
            'email' => 'director@sophia.com',
            'password' => Hash::make('password'),
            'role' => 'director',
        ]);

        // Secretary
        User::create([
            'name' => 'Secrétaire Sophia',
            'email' => 'secretary@sophia.com',
            'password' => Hash::make('password'),
            'role' => 'secretary',
        ]);

        // More users
        User::factory(5)->create(['role' => 'secretary']);
    }
}
