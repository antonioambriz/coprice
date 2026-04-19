<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transporter;

class TransporterSeeder extends Seeder
{
    public function run(): void
    {

    //Transporter::factory(10)->create();

        $transporters = [
            [
                'company_name'     => 'TRANSPORTES ESPECIALIZADOS COPRICE',
                'rfc'              => 'TEC100520ABC',
                'contact_person'   => 'ING. RICARDO MÉNDEZ',
                'email_remissions' => 'logistica@coprice.com',
                'address'          => 'AV. INDUSTRIAL 123, QUERÉTARO, QRO.',
                'activo'           => 1,
            ],
            [
                'company_name'     => 'LOGÍSTICA Y DISTRIBUCIÓN DEL BAJÍO S.A. DE C.V.',
                'rfc'              => 'LDB150822XYZ',
                'contact_person'   => 'LIC. MARÍA ESTRADA',
                'email_remissions' => 'facturacion@logibajio.mx',
                'address'          => 'PARQUE INDUSTRIAL BALVANERA, CORREGIDORA, QRO.',
                'activo'           => 1,
            ],
            [
                'company_name'     => 'SERVICIOS AMBIENTALES INTEGRALES S.A.',
                'rfc'              => 'SAI980115HJK',
                'contact_person'   => 'JUAN CARLOS PARRA',
                'email_remissions' => 'operaciones@ambientales.com',
                'address'          => 'CALLE 5 DE MAYO #45, CELAYA, GTO.',
                'activo'           => 1,
            ],
            [
                'company_name'     => 'TRANSPORTES INTERMODALES DE MÉXICO',
                'rfc'              => 'TIM051130123',
                'contact_person'   => 'ING. ALBERTO RUIZ',
                'email_remissions' => 'remisiones@intermodales.mx',
                'address'          => 'LIBRAMIENTO SUR PONIENTE KM 15, QRO.',
                'activo'           => 1,
            ],
            [
                'company_name'     => 'SOLUCIONES EN LOGÍSTICA TERRESTRE',
                'rfc'              => 'SLT200202TT1',
                'contact_person'   => 'SOFÍA REYES',
                'email_remissions' => 'administracion@slt.com.mx',
                'address'          => 'CONOCIDO, SAN JUAN DEL RÍO, QRO.',
                'activo'           => 1,
            ]
        ];

        foreach ($transporters as $transporter) {
            Transporter::updateOrCreate(['rfc' => $transporter['rfc']], $transporter);
        }

        $this->command->info("Se han cargado " . count($transporters) . " transportistas al catálogo.");
    }
}
