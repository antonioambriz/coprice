<?php

namespace Database\Seeders;

use App\Models\FinalDestination;
use Illuminate\Database\Seeder;

class FinalDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            ['name' => 'COPRICE',  'authorization_number' => 'CEL-PRME-061/2009'],
            ['name' => 'OPRESA',   'authorization_number' => 'CEL-PRME-036/2008'],
        ];

        foreach ($destinations as $d) {
            FinalDestination::create($d);
        }

        $this->command->info('Se cargaron ' . count($destinations) . ' destinos finales.');
    }
}
