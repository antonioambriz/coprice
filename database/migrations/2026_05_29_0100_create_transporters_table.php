<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transporters', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('rfc', 13)->nullable()->unique();
            $table->string('authorization_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email_remissions')->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporters');
    }
};
