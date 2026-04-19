<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->boolean('requires_transport_equipment')->default(false)->after('requires_manifest');
            $table->foreignId('transport_equipment_id')->nullable()->after('requires_transport_equipment')
                ->constrained('transport_equipments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign(['transport_equipment_id']);
            $table->dropColumn(['requires_transport_equipment', 'transport_equipment_id']);
        });
    }
};
