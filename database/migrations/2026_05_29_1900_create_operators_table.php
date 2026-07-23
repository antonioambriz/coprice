<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('transporters')->cascadeOnDelete();
            $table->string('name');
            $table->string('license_number')->nullable();
            $table->string('phone')->nullable();
            $table->date('license_expiry')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
