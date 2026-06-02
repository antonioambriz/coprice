<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'page_key'];

    // Roles configurables (SUPERADMIN siempre tiene acceso total, hardcodeado)
    public const ROLES = [
        'FACTURACION' => 'Facturación',
        'AMBIENTAL'   => 'Ambiental',
        'CONSULTA'    => 'Consulta',
    ];

    // Páginas del sistema con su clave, etiqueta e ícono
    public const PAGES = [
        'dashboard'         => ['label' => 'Inicio / Dashboard',    'icon' => 'tabler-home',               'system' => false],
        'generators'        => ['label' => 'Generadores',           'icon' => 'tabler-building-factory-2', 'system' => false],
        'clients'           => ['label' => 'Clientes',              'icon' => 'tabler-briefcase',          'system' => false],
        'transporters'      => ['label' => 'Transportistas',        'icon' => 'tabler-truck-delivery',     'system' => false],
        'wastes'            => ['label' => 'Residuos',              'icon' => 'tabler-recycle',            'system' => false],
        'final-destinations'=> ['label' => 'Destinos Finales',      'icon' => 'tabler-map-pin',            'system' => false],
        'manifests'         => ['label' => 'Manifiestos',           'icon' => 'tabler-file-text',          'system' => false],
        'remisions'         => ['label' => 'Remisiones',            'icon' => 'tabler-receipt',            'system' => false],
        'withdrawals'       => ['label' => 'Retiros',               'icon' => 'tabler-file-invoice',       'system' => false],
        'users'             => ['label' => 'Usuarios',              'icon' => 'tabler-users',              'system' => true],
        'permissions'       => ['label' => 'Permisos de acceso',    'icon' => 'tabler-shield-lock',        'system' => true],
    ];

    // Caché por request para evitar múltiples consultas
    private static ?array $cache = null;

    public static function hasPermission(string $role, string $pageKey): bool
    {
        if (self::$cache === null) {
            self::$cache = static::all()
                ->groupBy('role')
                ->map(fn($rows) => $rows->pluck('page_key')->toArray())
                ->toArray();
        }

        return in_array($pageKey, self::$cache[$role] ?? []);
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }
}
