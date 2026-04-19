<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@coprice.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@coprice.com',
                'password' => Hash::make('Admin1234!'),
                'email_verified_at' => now(),
            ]
        );
    }
}
