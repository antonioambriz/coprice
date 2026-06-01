<?php

namespace Database\Seeders;

use App\Models\Transporter;
use Illuminate\Database\Seeder;

class TransporterSeeder extends Seeder
{
    public function run(): void
    {
        $transporters = [
            ['company_name' => 'AGUAS Y DRENAJES',                 'authorization_number' => 'IRA-PRME-516/2016'],
            ['company_name' => 'ALEJANDRO',                         'authorization_number' => null],
            ['company_name' => 'BACHOCO (CEDIS)',                   'authorization_number' => null],
            ['company_name' => 'COPRICE',                           'authorization_number' => 'CEL-PRME-061/2009'],
            ['company_name' => 'CR SOLUCIONES EN MOVIMIENTO',      'authorization_number' => null],
            ['company_name' => 'Desperdicios Queretana',            'authorization_number' => 'SEDESU/RPPSA/229-09'],
            ['company_name' => 'DESOLTEC',                          'authorization_number' => null],
            ['company_name' => 'GEN',                               'authorization_number' => 'IRA-PRME-329/2014'],
            ['company_name' => 'GGS',                               'authorization_number' => null],
            ['company_name' => 'GREGORIO',                          'authorization_number' => null],
            ['company_name' => 'JOSE ALFREDO SANABRIA VARGAS',     'authorization_number' => 'IRA-PRME-011/2008'],
            ['company_name' => 'LILIANA ALMAGUER',                  'authorization_number' => 'IRA-PRME-593/2017'],
            ['company_name' => 'LSP',                               'authorization_number' => 'IRA-PRME-204/2012'],
            ['company_name' => 'MA. ALEJANDRA LANDA MUÑOZ',       'authorization_number' => 'CEL-PRME-617/2017'],
            ['company_name' => 'MONBA',                             'authorization_number' => 'SIL-PRME-804/2019'],
            ['company_name' => 'RECICLAJE Y RECOLECCIÓN',          'authorization_number' => 'CEL-PRME-122/2011'],
            ['company_name' => 'SECOMI CONTRACTORS',                'authorization_number' => 'IRA-PRME-763/2019'],
            ['company_name' => 'SECOMI TÉCNICOS',                  'authorization_number' => 'LEN-PRME-824/2019'],
            ['company_name' => 'ZEROLANDFILL',                      'authorization_number' => 'CEL-PRME-756/2018'],
        ];

        foreach ($transporters as $t) {
            Transporter::create(array_merge($t, ['activo' => 1]));
        }

        $this->command->info('Se cargaron ' . count($transporters) . ' transportistas.');
    }
}
