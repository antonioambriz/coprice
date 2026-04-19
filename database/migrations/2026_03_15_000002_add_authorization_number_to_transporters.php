<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->string('authorization_number')->nullable()->after('rfc');
        });
    }

    public function down(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->dropColumn('authorization_number');
        });
    }
};
