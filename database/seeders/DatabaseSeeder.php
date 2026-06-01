<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WasteSeeder::class,
            FinalDestinationSeeder::class,
            GeneratorSeeder::class,
            TransporterSeeder::class,
            ClientSeeder::class,
            ClientGeneratorWasteSeeder::class,
        ]);
    }
}
