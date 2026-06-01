<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remisions', function (Blueprint $table) {
            $table->id();
            $table->string('remision_number')->nullable();
            $table->foreignId('generator_id')->constrained();
            $table->foreignId('sub_generator_id')->nullable()->constrained()->nullOnDelete();
            $table->date('emission_date')->nullable();
            $table->string('status')->default('BORRADOR');
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remisions');
    }
};
