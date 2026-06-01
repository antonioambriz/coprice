<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\FinalDestination;
use App\Models\Generator;
use App\Models\Waste;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientGeneratorWasteSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['generator' => 'MA. ALEJANDRA LANDA MUÑOZ',                                          'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'ANDROID DE MÉXICO S. DE R.L. DE C.V',                               'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO (PTAR)',                                                      'client' => 'BACHOCO',                                        'waste' => 'Lodo aguado',                                'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO (PTAR)',                                                      'client' => 'BACHOCO',                                        'waste' => 'Lodo prensado',                              'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO (DESAZOLVE)',                                                 'client' => 'BACHOCO',                                        'waste' => 'Lodos de desazolve',                         'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO (RENDIMIENTOS)',                                              'client' => 'BACHOCO',                                        'waste' => 'Residuos orgánicos (pluma, vísceras y sangre)', 'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO, S.A. DE C.V. (CEDIS CELAYA)',                               'client' => 'BACHOCO',                                        'waste' => 'Desperdicio de pollo',                       'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO, S.A. DE C.V. (CEDIS QRO)',                                  'client' => 'BACHOCO',                                        'waste' => 'Desperdicio de pollo',                       'destination' => 'COPRICE'],
            ['generator' => 'BACHOCO, S.A. DE C.V. (CEDIS IRAPUATO)',                             'client' => 'BACHOCO',                                        'waste' => 'Mortalidad',                                 'destination' => 'COPRICE'],
            ['generator' => 'Beiersdorf Manufacturing México, S.A. de C.V.',                      'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'Robert Bosch México Sistemas Automotrices, S.A. de C.V.',            'client' => 'Desperdicios Queretana',                         'waste' => 'Orgánicos (comedor)',                         'destination' => 'COPRICE'],
            ['generator' => 'Robert Bosch México Sistemas Automotrices, S.A. de C.V. (Planta Apaseo)', 'client' => 'Desperdicios Queretana',                    'waste' => 'Orgánicos (comedor)',                         'destination' => 'COPRICE'],
            ['generator' => 'B&G FOODS MANUFACTIRING MÉXICO, S. DE R.L. DE C.V.',                'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'B&G FOODS MANUFACTIRING MÉXICO, S. DE R.L. DE C.V.',                'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'BIMBO S.A DE C.V.',                                                  'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'EMPACADORA DE CELAYA S.A. DE C.V.',                                  'client' => 'CR SOLUCIONES EN MOVIMIENTO',                    'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'Corrugados Especializados del Bajío, S.A. de C.V.',                  'client' => 'Corrugados Especializados del Bajío, S.A. de C.V.', 'waste' => 'Residuos Sólidos Urbanos',               'destination' => 'OPRESA'],
            ['generator' => 'Corrugados Especializados del Bajío, S.A. de C.V.',                  'client' => 'Corrugados Especializados del Bajío, S.A. de C.V.', 'waste' => 'Tarima',                                 'destination' => 'OPRESA'],
            ['generator' => 'Corrugados Especializados del Bajío, S.A. de C.V.',                  'client' => 'Corrugados Especializados del Bajío, S.A. de C.V.', 'waste' => 'Lodos de PTAR',                          'destination' => 'OPRESA'],
            ['generator' => 'Galería Productora de Cosméticos, S. de R.L. de C.V.',               'client' => 'RECICLAJE Y RECOLECCIÓN',                        'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Danfoss Power Solutions S de R.L. de C.V',                           'client' => 'Desperdicios Queretana',                         'waste' => 'Orgánicos (celulosa)',                        'destination' => 'COPRICE'],
            ['generator' => 'DANONE DE MEXICO, S.A. de C.V.',                                     'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Desperdicio de yogurt',                      'destination' => 'COPRICE'],
            ['generator' => 'DANONE DE MEXICO, S.A. de C.V.',                                     'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'DANONE DE MEXICO, S.A. de C.V.',                                     'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V (ALAMBRES)',                                'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V (ALAMBRES)',                                'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Torta',                                      'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V (SUMMIT)',                                  'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Torta',                                      'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V (SUMMIT)',                                  'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V.-(PLANTA TREFILADOS)',                      'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'DEACERO S.A.P.I. DE C.V (AYD)',                                     'client' => 'MA. ALEJANDRA LANDA MUÑOZ',                      'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Effem México INC y Compañía, S. en N.C. de C.V.',                    'client' => 'Desperdicios Queretana',                         'waste' => 'Orgánicos (cárnicos)',                        'destination' => 'COPRICE'],
            ['generator' => 'Effem México INC y Compañía, S. en N.C. de C.V.',                    'client' => 'Desperdicios Queretana',                         'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Effem México INC y Compañía, S. en N.C. de C.V.',                    'client' => 'Desperdicios Queretana',                         'waste' => 'Orgánicos (comedor)',                         'destination' => 'COPRICE'],
            ['generator' => 'Effem México INC y Compañía, S. en N.C. de C.V.',                    'client' => 'Desperdicios Queretana',                         'waste' => 'Residuos orgánicos (poda)',                   'destination' => 'COPRICE'],
            ['generator' => 'EMPACADORA CELAYA S.A. DE C.V',                                      'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'Ford Motor Company, S.A. de C.V.',                                   'client' => 'GEN',                                            'waste' => 'Orgánicos (comedor)',                         'destination' => 'COPRICE'],
            ['generator' => 'Ford Motor Company, S.A. de C.V.',                                   'client' => 'GEN',                                            'waste' => 'Residuos orgánicos (poda)',                   'destination' => 'COPRICE'],
            ['generator' => 'F&P MFG DE MEXICO S.A DE C.V',                                      'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'Gigante Verde, S. de R.L. de C.V.',                                  'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'HENKEL CAPITAL, S.A. DE C.V',                                        'client' => 'GEN',                                            'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'HERDEZ S.A.DE C.V',                                                  'client' => 'GEN',                                            'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'Honda de México, S.A. de C.V.',                                      'client' => 'JOEL LIPKIES',                                   'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'Honda de México, S.A. de C.V.',                                      'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'HOSPITAL',                                                            'client' => 'SALUD PÚBLICA DEL ESTADO',                       'waste' => 'Residuos Sólidos Urbanos',                   'destination' => 'COPRICE'],
            ['generator' => 'ITT MOTION RECHNOLOGIES MEXICO S. DE R.L DE C.V',                   'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'J Clima Sistemas México, S.A. de C.V.',                              'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'Comercializadora de Lácteos y Derivados, S.A. de C.V.',              'client' => 'LALA',                                           'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Comercializadora de Lácteos y Derivados, S.A. de C.V.',              'client' => 'LALA',                                           'waste' => 'Lodos de PTAR (Contenedor)',                  'destination' => 'COPRICE'],
            ['generator' => 'Comercializadora de Lácteos y Derivados, S.A. de C.V.',              'client' => 'LALA',                                           'waste' => 'Desperdicio de leche',                       'destination' => 'COPRICE'],
            ['generator' => 'Comercializadora de Lácteos y Derivados, S.A. de C.V.',              'client' => 'LALA',                                           'waste' => 'Desperdicio de yogurt',                      'destination' => 'COPRICE'],
            ['generator' => 'Pasteurizadora de León S.A de C.V.',                                 'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Leche ácida',                                'destination' => 'COPRICE'],
            ['generator' => 'Maquinados Automotrices y Talleres Industriales de Celaya, S.A. de C.V.', 'client' => 'COPRICE',                                   'waste' => 'Madera',                                     'destination' => 'COPRICE'],
            ['generator' => 'Maquinados de Precisión de México S de R.L. de C.V.',                'client' => 'COPRICE',                                        'waste' => 'Madera',                                     'destination' => 'COPRICE'],
            ['generator' => 'Mazda Motor Manufacturing de México, S.A. de C.V.',                  'client' => 'LILIANA ALMAGUER',                               'waste' => 'Orgánicos (comedor)',                         'destination' => 'COPRICE'],
            ['generator' => 'Mazda Motor Manufacturing de México, S.A. de C.V.',                  'client' => 'LILIANA ALMAGUER',                               'waste' => 'Residuos sólidos de criba',                  'destination' => 'COPRICE'],
            ['generator' => 'Mazda Motor Manufacturing de México, S.A. de C.V.',                  'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'MARQUARDT MÉXICO, S.R.L. DE C.V',                                   'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'MINIBEA ACCESSSOLITIONS MÉXICO S.A DE C.V',                          'client' => 'CARLOS ENRIQUE RAMIREZ RAMIREZ',                 'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'NIDEC Mobility México S.DE.R.L.DE.C.V',                             'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Nucor-JFE-Steel México',                                             'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'HARINERA LOS PIRINEOS, S.A DE C.V.',                                'client' => 'CARLOS ENRIQUE RAMIREZ RAMIREZ',                 'waste' => 'Residuos orgánicos (poda)',                   'destination' => 'COPRICE'],
            ['generator' => 'PC BIOLOGICS, S.A. DE C.V.',                                         'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Residuo especial de proceso',                 'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Almidón',                                    'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos con tinta',                            'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'COPRICE',                                        'waste' => 'Bandas y tapetes',                           'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'COPRICE',                                        'waste' => 'Llantas',                                    'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'COPRICE',                                        'waste' => 'Plástico',                                   'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'COPRICE',                                        'waste' => 'Madera',                                     'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'COPRICE',                                        'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Almidón',                                    'destination' => 'COPRICE'],
            ['generator' => 'Papel, Cartón y Derivados, S. de R.L. de C.V.',                     'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Lodos con tinta',                            'destination' => 'COPRICE'],
            ['generator' => 'RDCM, S. de R.L. de C.V.',                                           'client' => 'CARLOS ENRIQUE RAMIREZ RAMIREZ',                 'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'GRUPO GAMESA S. DE R.L. DE C.V.',                                   'client' => 'ZEROLANDFILL',                                   'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'JOSE ALFREDO SANABRIA VARGAS',                                       'client' => 'JOSE ALFREDO SANABRIA VARGAS',                   'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Schaeffler México, S. de R.L. de C.V.',                              'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'Secomi Contractors, S. de R.L. de C.V.',                             'client' => 'SECOMI CONTRACTORS',                             'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'Secomi Contractors, S. de R.L. de C.V.',                             'client' => 'SECOMI CONTRACTORS',                             'waste' => 'Residuos orgánicos (destrucción)',            'destination' => 'COPRICE'],
            ['generator' => 'Sensient Flavors México, S.A. de C.V.',                              'client' => 'COPRICE',                                        'waste' => '1a Filtración PVH',                          'destination' => 'COPRICE'],
            ['generator' => 'Sensient Flavors México, S.A. de C.V.',                              'client' => 'COPRICE',                                        'waste' => '2a Filtración PVH',                          'destination' => 'COPRICE'],
            ['generator' => 'Sensient Flavors México, S.A. de C.V.',                              'client' => 'COPRICE',                                        'waste' => 'Yestal',                                     'destination' => 'COPRICE'],
            ['generator' => 'Sensient Flavors México, S.A. de C.V.',                              'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Lodos de desazolve',                         'destination' => 'COPRICE'],
            ['generator' => 'Sensient Flavors México, S.A. de C.V.',                              'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de desazolve',                         'destination' => 'COPRICE'],
            ['generator' => 'Sigma Alimentos Centro, S.A. de C.V. (PLANTA PÉNJAMO)',              'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Sigma Alimentos Lacteos, S.A. de C.V. (PLANTA CELAYA)',              'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Smurfit Cartón y Papel de México, S.A. de C.V.',                    'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Toyota Motor Manufacturing de Guanajuato, S.A. de C.V.',             'client' => 'ALEJANDRO',                                      'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'Toyota Motor Manufacturing de Guanajuato, S.A. de C.V.',             'client' => 'MONBA',                                          'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'VALGROUP',                                                            'client' => 'GREGORIO',                                       'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'VISCOFAN DE MÉXICO S.R. DE C.V.',                                   'client' => 'GEN',                                            'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'VISCOFAN DE MÉXICO S.R. DE C.V.',                                   'client' => 'GEN',                                            'waste' => 'Residuos orgánicos (destrucción)',            'destination' => 'COPRICE'],
            ['generator' => 'XMAX-SCHERDEL DE México , S. de R.L. de C.V.',                      'client' => 'CARLOS ENRIQUE RAMIREZ RAMIREZ',                 'waste' => 'Residuos orgánicos',                         'destination' => 'COPRICE'],
            ['generator' => 'YKK Mexicana, S.A. De C.V.',                                         'client' => 'GEN',                                            'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'YKK Mexicana, S.A. De C.V.',                                         'client' => 'SECOMI TÉCNICOS',                                'waste' => 'Lodos de PTAR',                              'destination' => 'COPRICE'],
            ['generator' => 'Y-Tec Keylex México, S.A. de C.V.',                                  'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
            ['generator' => 'ZKW México, S.A. de C.V.',                                           'client' => 'AGUAS Y DRENAJES',                               'waste' => 'Grasas vegetales',                           'destination' => 'COPRICE'],
        ];

        $errors = 0;

        foreach ($rows as $row) {
            $generator   = Generator::where('company_name', $row['generator'])->first();
            $client      = Client::where('company_name', $row['client'])->first();
            $waste       = Waste::whereRaw('LOWER(description) = LOWER(?)', [$row['waste']])->first();
            $destination = FinalDestination::where('name', $row['destination'])->first();

            if (!$generator || !$client || !$waste) {
                $this->command->warn("Saltando: {$row['generator']} | {$row['client']} | {$row['waste']}");
                $errors++;
                continue;
            }

            DB::table('client_generator_wastes')->updateOrInsert(
                [
                    'client_id'       => $client->id,
                    'generator_id'    => $generator->id,
                    'sub_generator_id'=> null,
                    'waste_id'        => $waste->id,
                ],
                [
                    'final_destination_id' => $destination?->id,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]
            );
        }

        $loaded = count($rows) - $errors;
        $this->command->info("Se cargaron {$loaded} relaciones cliente-generador-residuo. Errores: {$errors}.");
    }
}
