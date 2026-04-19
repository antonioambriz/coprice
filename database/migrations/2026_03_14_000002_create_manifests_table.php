<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_number')->nullable();

            // Puede pertenecer a un sub-generador o directamente a un generador
            $table->foreignId('generator_id')->constrained();
            $table->foreignId('sub_generator_id')->nullable()->constrained()->nullOnDelete();

            // Para manifiestos semanales (Incubadora): rango de fechas que agrupa
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            // Si el manifiesto fue efectivamente generado/entregado
            $table->boolean('generated')->default(false);

            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
