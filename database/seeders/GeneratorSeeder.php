<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Generator::updateOrCreate(['rfc' => 'BAC123456789'], [
        'company_name' => 'Bachoco S.A. de C.V.',
        'rfc' => 'BAC123456789',
        'address' => 'Celaya, Guanajuato',
        'status' => true
    ]);

    \App\Models\Generator::updateOrCreate(['rfc' => 'HUT987654321'], [
        'company_name' => 'Hutchinson Seal',
        'rfc' => 'HUT987654321',
        'address' => 'Querétaro, Qro.',
        'status' => true
    ]);
}
}
