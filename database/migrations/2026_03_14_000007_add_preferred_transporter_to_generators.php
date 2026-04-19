<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generators', function (Blueprint $table) {
            $table->foreignId('preferred_transporter_id')
                  ->nullable()
                  ->after('has_sub_generators')
                  ->constrained('transporters')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('generators', function (Blueprint $table) {
            $table->dropForeign(['preferred_transporter_id']);
            $table->dropColumn('preferred_transporter_id');
        });
    }
};
