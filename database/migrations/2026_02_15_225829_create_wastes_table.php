<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('wastes', function (Blueprint $table) {
        $table->id();
        $table->string('description');
        $table->string('waste_code')->nullable();
        $table->string('unit');
        $table->decimal('default_price', 10, 2)->default(0);
        $table->boolean('is_hazardous')->default(false);
        $table->text('notes')->nullable(); // <-- AGREGA ESTA LÍNEA
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wastes');
    }
};
