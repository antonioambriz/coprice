<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('transporters')->cascadeOnDelete();
            $table->string('description');
            $table->string('plate_number')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_equipments');
    }
};
