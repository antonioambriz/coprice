<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generators', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('rfc', 13)->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('has_sub_generators')->default(false);
            $table->boolean('requires_manifest')->default(false);
            $table->foreignId('preferred_transporter_id')->nullable()->constrained('transporters')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generators');
    }
};
