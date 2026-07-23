<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('address');

            $table->string('street')->nullable()->after('rfc');
            $table->string('ext_number')->nullable()->after('street');
            $table->string('int_number')->nullable()->after('ext_number');
            $table->string('municipality')->nullable()->after('int_number');
            $table->string('state')->nullable()->after('municipality');
            $table->string('postal_code', 5)->nullable()->after('state');
            $table->string('country')->default('México')->after('postal_code');
            $table->string('payment_method', 2)->nullable()->after('country');
            $table->unsignedSmallInteger('credit_days')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'street',
                'ext_number',
                'int_number',
                'municipality',
                'state',
                'postal_code',
                'country',
                'payment_method',
                'credit_days',
            ]);

            $table->text('address')->nullable()->after('rfc');
        });
    }
};
