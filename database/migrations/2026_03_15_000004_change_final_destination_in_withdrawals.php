<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn('final_destination');
            $table->foreignId('final_destination_id')->nullable()->constrained('final_destinations')->nullOnDelete()->after('treatment_stage');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign(['final_destination_id']);
            $table->dropColumn('final_destination_id');
            $table->string('final_destination')->nullable()->after('treatment_stage');
        });
    }
};
