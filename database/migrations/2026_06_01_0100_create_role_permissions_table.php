<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role', 50);
            $table->string('page_key', 100);
            $table->timestamps();
            $table->unique(['role', 'page_key']);
        });

        // Permisos iniciales por defecto
        $defaults = [
            ['role' => 'FACTURACION', 'page_key' => 'dashboard'],
            ['role' => 'FACTURACION', 'page_key' => 'clients'],
            ['role' => 'FACTURACION', 'page_key' => 'manifests'],
            ['role' => 'FACTURACION', 'page_key' => 'remisions'],
            ['role' => 'FACTURACION', 'page_key' => 'withdrawals'],
            ['role' => 'AMBIENTAL',   'page_key' => 'dashboard'],
            ['role' => 'AMBIENTAL',   'page_key' => 'generators'],
            ['role' => 'AMBIENTAL',   'page_key' => 'clients'],
            ['role' => 'AMBIENTAL',   'page_key' => 'transporters'],
            ['role' => 'AMBIENTAL',   'page_key' => 'wastes'],
            ['role' => 'AMBIENTAL',   'page_key' => 'final-destinations'],
            ['role' => 'AMBIENTAL',   'page_key' => 'manifests'],
            ['role' => 'AMBIENTAL',   'page_key' => 'remisions'],
            ['role' => 'AMBIENTAL',   'page_key' => 'withdrawals'],
            ['role' => 'CONSULTA',    'page_key' => 'dashboard'],
            ['role' => 'CONSULTA',    'page_key' => 'manifests'],
            ['role' => 'CONSULTA',    'page_key' => 'withdrawals'],
        ];

        $now = now();
        DB::table('role_permissions')->insert(
            array_map(fn($r) => array_merge($r, ['created_at' => $now, 'updated_at' => $now]), $defaults)
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
