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
            ['name' => 'Admin',              'email' => 'admin@coprice.com',            'role' => 'SUPERADMIN'],
            ['name' => 'Maribél Calderón',   'email' => 'mcalderon@coprice.com.mx',     'role' => 'FACTURACION'],
            ['name' => 'Guadalupe Sandoval', 'email' => 'gsandoval@coprice.com.mx',     'role' => 'AMBIENTAL'],
            ['name' => 'Miguel Mata',        'email' => 'mmata@coprice.com.mx',         'role' => 'AMBIENTAL'],
            ['name' => 'Gabriela Solís',     'email' => 'gaby.solis.hdz@gmail.com',     'role' => 'AMBIENTAL'],
            ['name' => 'Pastor Bañuelos',    'email' => 'pbanuelos@coprice.com.mx',     'role' => 'CONSULTA'],
            ['name' => 'David Solís',        'email' => 'dsolisojeda@hotmail.com',      'role' => 'CONSULTA'],
            ['name' => 'Daniela Solís',      'email' => 'dany.solis.03@hotmail.com',    'role' => 'SUPERADMIN'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name'              => $user['name'],
                    'role'              => $user['role'],
                    'password'          => Hash::make('Coprice2025!'),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Se cargaron ' . count($users) . ' usuarios.');
    }
}
