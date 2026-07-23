<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No existe una correspondencia válida transportista → cliente
        // (catálogos independientes), así que se empieza en limpio.
        DB::table('waste_prices')->truncate();

        Schema::table('waste_prices', function (Blueprint $table) {
            $table->dropForeign(['transporter_id']);
            $table->dropColumn('transporter_id');
        });

        Schema::table('waste_prices', function (Blueprint $table) {
            $table->foreignId('client_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['client_id', 'waste_id']);
        });
    }

    public function down(): void
    {
        Schema::table('waste_prices', function (Blueprint $table) {
            $table->dropUnique(['client_id', 'waste_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::table('waste_prices', function (Blueprint $table) {
            $table->foreignId('transporter_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }
};
