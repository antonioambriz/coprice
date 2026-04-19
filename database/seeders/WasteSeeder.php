<?php

namespace Database\Seeders;

use App\Models\Waste;
use Illuminate\Database\Seeder;

class WasteSeeder extends Seeder
{
    public function run(): void
    {
        $wastes = [
            ['description' => 'LODO ACUOSO DE PTAR', 'unit' => 'KG', 'is_hazardous' => true],
            ['description' => 'LODO FISICOQUÍMICO', 'unit' => 'KG', 'is_hazardous' => true],
            ['description' => 'LODOS DE PTAR', 'unit' => 'KG', 'is_hazardous' => true],
            ['description' => 'RESIDUOS ORGÁNICOS', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RESIDUOS ORGÁNICOS (COMEDOR)', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RESIDUOS ORGÁNICOS (H,E,C)', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RME (POLVOS DE COLECTOR MIXING)', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'DESPERDICIO DE LECHE', 'unit' => 'LT', 'is_hazardous' => false],
            ['description' => 'DESPERDICIO DE YOGHURT', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'GRASAS VEGETALES', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RSU', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'VALORIZABLES', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'LECHE ÁCIDA', 'unit' => 'LT', 'is_hazardous' => false],
            ['description' => 'MADERA', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RESIDUOS SÓLIDOS DE CRIBA', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RESIDUOS ORGÁNICOS (PODA)', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'LODO RESIDUAL (BASE SECA)', 'unit' => 'KG', 'is_hazardous' => true],
            ['description' => 'LODOS DE CARCAMO', 'unit' => 'KG', 'is_hazardous' => true],
            ['description' => 'GRASA CON AGUA', 'unit' => 'KG', 'is_hazardous' => false],
            ['description' => 'RESIDUO ESPECIAL DE PROCESO', 'unit' => 'KG', 'is_hazardous' => true],
        ];

        foreach ($wastes as $waste) {
            Waste::updateOrCreate(
                ['description' => strtoupper($waste['description'])],
                [
                    'unit'          => strtoupper($waste['unit']),
                    'default_price' => 0.00,
                    'is_hazardous'  => $waste['is_hazardous'],
                    'waste_code'    => 'CAT-' . rand(1000, 9999),
                ]
            );
        }

        $this->command->info("Se han cargado " . count($wastes) . " residuos generales al catálogo.");
    }
}
