<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_generator_wastes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('generator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_generator_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('waste_id')->constrained()->cascadeOnDelete();
            $table->foreignId('final_destination_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique(['client_id', 'generator_id', 'sub_generator_id', 'waste_id'], 'cgw_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_generator_wastes');
    }
};
