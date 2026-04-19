<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('folio_interno')->unique();
            $table->string('ticket_externo')->nullable();
            $table->string('folio_salida')->nullable();

            $table->foreignId('generator_id')->constrained();
            $table->foreignId('sub_generator_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('transporter_id')->constrained();
            $table->foreignId('manifest_id')->nullable()->constrained()->nullOnDelete();

            $table->date('reception_date');
            $table->dateTime('departure_date')->nullable();

            $table->boolean('is_estimated_weight')->default(false);
            $table->string('estimated_weight_reason')->nullable();

            $table->string('treatment_stage')->default('TRATAMIENTO');
            $table->string('payment_status')->default('PENDIENTE');
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
