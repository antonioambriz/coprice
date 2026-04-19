<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_id')->constrained()->onDelete('cascade');
            $table->foreignId('waste_id')->constrained();

            $table->decimal('quantity', 12, 3);
            $table->string('unit')->default('KG');

            $table->string('physical_state')->nullable();
            $table->string('packaging_type')->nullable();
            $table->string('container_capacity')->nullable();
            $table->string('container_type')->nullable();

            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2)->storedAs('quantity * unit_price');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_items');
    }
};
