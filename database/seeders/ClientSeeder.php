<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            'AGUAS Y DRENAJES',
            'ALEJANDRO',
            'BACHOCO',
            'CARLOS ENRIQUE RAMIREZ RAMIREZ',
            'COPRICE',
            'Corrugados Especializados del Bajío, S.A. de C.V.',
            'CR SOLUCIONES EN MOVIMIENTO',
            'Desperdicios Queretana',
            'GEN',
            'GREGORIO',
            'JOEL LIPKIES',
            'JOSE ALFREDO SANABRIA VARGAS',
            'LALA',
            'LILIANA ALMAGUER',
            'MA. ALEJANDRA LANDA MUÑOZ',
            'MONBA',
            'RECICLAJE Y RECOLECCIÓN',
            'SALUD PÚBLICA DEL ESTADO',
            'SECOMI CONTRACTORS',
            'SECOMI TÉCNICOS',
            'ZEROLANDFILL',
        ];

        foreach ($clients as $name) {
            Client::create(['company_name' => $name, 'activo' => true]);
        }

        $this->command->info('Se cargaron ' . count($clients) . ' clientes.');
    }
}
