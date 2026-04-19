<?php

namespace Database\Factories;

use App\Models\Transporter;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransporterFactory extends Factory
{
    protected $model = \App\Models\content\Transporter::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'rfc' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'contacto_nombre' => $this->faker->name(),
            'activo' => 1,
        ];
    }
}
