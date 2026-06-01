<?php

namespace Database\Seeders;

use App\Models\Generator;
use Illuminate\Database\Seeder;

class GeneratorSeeder extends Seeder
{
    public function run(): void
    {
        $generators = [
            'ANDROID DE MÉXICO S. DE R.L. DE C.V',
            'B&G FOODS MANUFACTIRING MÉXICO, S. DE R.L. DE C.V.',
            'BACHOCO (DESAZOLVE)',
            'BACHOCO (PTAR)',
            'BACHOCO (RENDIMIENTOS)',
            'BACHOCO, S.A. DE C.V. (CEDIS CELAYA)',
            'BACHOCO, S.A. DE C.V. (CEDIS IRAPUATO)',
            'BACHOCO, S.A. DE C.V. (CEDIS QRO)',
            'Beiersdorf Manufacturing México, S.A. de C.V.',
            'BIMBO S.A DE C.V.',
            'Comercializadora de Lácteos y Derivados, S.A. de C.V.',
            'Corrugados Especializados del Bajío, S.A. de C.V.',
            'Danfoss Power Solutions S de R.L. de C.V',
            'DANONE DE MEXICO, S.A. de C.V.',
            'DEACERO S.A.P.I. DE C.V (ALAMBRES)',
            'DEACERO S.A.P.I. DE C.V (AYD)',
            'DEACERO S.A.P.I. DE C.V (SUMMIT)',
            'DEACERO S.A.P.I. DE C.V.-(PLANTA TREFILADOS)',
            'Effem México INC y Compañía, S. en N.C. de C.V.',
            'EMPACADORA CELAYA S.A. DE C.V',
            'EMPACADORA DE CELAYA S.A. DE C.V.',
            'F&P MFG DE MEXICO S.A DE C.V',
            'Ford Motor Company, S.A. de C.V.',
            'Galería Productora de Cosméticos, S. de R.L. de C.V.',
            'Gigante Verde, S. de R.L. de C.V.',
            'GRUPO GAMESA S. DE R.L. DE C.V.',
            'HARINERA LOS PIRINEOS, S.A DE C.V.',
            'HENKEL CAPITAL, S.A. DE C.V',
            'HERDEZ S.A.DE C.V',
            'Honda de México, S.A. de C.V.',
            'HOSPITAL',
            'ITT MOTION RECHNOLOGIES MEXICO S. DE R.L DE C.V',
            'J Clima Sistemas México, S.A. de C.V.',
            'JOSE ALFREDO SANABRIA VARGAS',
            'Maquinados Automotrices y Talleres Industriales de Celaya, S.A. de C.V.',
            'Maquinados de Precisión de México S de R.L. de C.V.',
            'MA. ALEJANDRA LANDA MUÑOZ',
            'MARQUARDT MÉXICO, S.R.L. DE C.V',
            'Mazda Motor Manufacturing de México, S.A. de C.V.',
            'MINIBEA ACCESSSOLITIONS MÉXICO S.A DE C.V',
            'NIDEC Mobility México S.DE.R.L.DE.C.V',
            'Nucor-JFE-Steel México',
            'Papel, Cartón y Derivados, S. de R.L. de C.V.',
            'Pasteurizadora de León S.A de C.V.',
            'PC BIOLOGICS, S.A. DE C.V.',
            'RDCM, S. de R.L. de C.V.',
            'Robert Bosch México Sistemas Automotrices, S.A. de C.V.',
            'Robert Bosch México Sistemas Automotrices, S.A. de C.V. (Planta Apaseo)',
            'Schaeffler México, S. de R.L. de C.V.',
            'Secomi Contractors, S. de R.L. de C.V.',
            'Sensient Flavors México, S.A. de C.V.',
            'Sigma Alimentos Centro, S.A. de C.V. (PLANTA PÉNJAMO)',
            'Sigma Alimentos Lacteos, S.A. de C.V. (PLANTA CELAYA)',
            'Smurfit Cartón y Papel de México, S.A. de C.V.',
            'Toyota Motor Manufacturing de Guanajuato, S.A. de C.V.',
            'VALGROUP',
            'VISCOFAN DE MÉXICO S.R. DE C.V.',
            'XMAX-SCHERDEL DE México , S. de R.L. de C.V.',
            'YKK Mexicana, S.A. De C.V.',
            'Y-Tec Keylex México, S.A. de C.V.',
            'ZKW México, S.A. de C.V.',
        ];

        foreach ($generators as $name) {
            Generator::create([
                'company_name'       => $name,
                'status'             => true,
                'has_sub_generators' => false,
                'requires_manifest'  => false,
            ]);
        }

        $this->command->info('Se cargaron ' . count($generators) . ' generadores.');
    }
}
