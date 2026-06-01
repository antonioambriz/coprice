<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waste_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_per_ton', 10, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_prices');
    }
};
