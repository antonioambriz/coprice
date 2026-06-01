<?php

namespace Database\Seeders;

use App\Models\Waste;
use Illuminate\Database\Seeder;

class WasteSeeder extends Seeder
{
    public function run(): void
    {
        $wastes = [
            ['description' => '1a Filtración PVH',                             'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => '2a Filtración PVH',                             'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Almidón',                                        'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Bandas y tapetes',                              'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Chatarra',                                       'physical_state' => 'Sólido',      'stage' => 'Acopio'],
            ['description' => 'Desperdicio de leche',                          'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Desperdicio de pollo',                          'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Desperdicio de yogurt',                         'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Grasas vegetales',                              'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Leche ácida',                                   'physical_state' => 'Líquido',     'stage' => 'Tratamiento'],
            ['description' => 'Llantas',                                        'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Lodo aguado',                                   'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodo (fosa séptica)',                           'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodo prensado',                                 'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodo residual (base seca)',                     'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodos con tinta',                               'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodos de desazolve',                            'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodos de PTAR',                                 'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Lodos de PTAR (Contenedor)',                    'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Madera',                                        'physical_state' => 'Sólido',      'stage' => 'Reutilización'],
            ['description' => 'Mortalidad',                                    'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Orgánicos (cárnicos)',                          'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Orgánicos (celulosa)',                          'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Orgánicos (comedor)',                           'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Plástico',                                      'physical_state' => 'Sólido',      'stage' => 'Reutilización'],
            ['description' => 'Resina de suavizadores',                        'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
            ['description' => 'Residuo especial de proceso',                   'physical_state' => 'Líquido',     'stage' => 'Disposición final'],
            ['description' => 'Residuos orgánicos',                            'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos orgánicos (destrucción)',              'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos orgánicos (jardinería)',               'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos orgánicos (pluma, vísceras y sangre)', 'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos orgánicos (poda)',                     'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos sólidos de criba',                    'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Residuos Sólidos Urbanos',                     'physical_state' => 'Sólido',      'stage' => 'Destino Final'],
            ['description' => 'Tarima',                                        'physical_state' => 'Sólido',      'stage' => 'Reutilización'],
            ['description' => 'Torta',                                         'physical_state' => 'Sólido',      'stage' => 'Tratamiento'],
            ['description' => 'Yestal',                                        'physical_state' => 'Semi-sólido', 'stage' => 'Tratamiento'],
        ];

        foreach ($wastes as $waste) {
            Waste::create([
                'description'    => $waste['description'],
                'physical_state' => $waste['physical_state'],
                'stage'          => $waste['stage'],
                'unit'           => 'TON',
                'default_price'  => 0.00,
                'is_hazardous'   => false,
            ]);
        }

        $this->command->info('Se cargaron ' . count($wastes) . ' residuos al catálogo.');
    }
}
