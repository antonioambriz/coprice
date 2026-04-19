<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_generators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generator_id')->constrained()->cascadeOnDelete();
            $table->string('name');

            // Peso asumido cuando no hay báscula (ej. domingos)
            $table->decimal('assumed_weight', 10, 2)->nullable();

            // Frecuencia de reporte: weekly, monthly, sporadic
            $table->string('report_frequency')->default('sporadic');

            // Si por defecto se genera manifiesto en sus retiros
            $table->boolean('requires_manifest')->default(false);

            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_generators');
    }
};
