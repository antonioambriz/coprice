<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    // Mapeo: page_key => prefijos de nombre de ruta que pertenecen a esa página
    private const PAGE_ROUTE_MAP = [
        'dashboard'          => ['dashboard-analytics', 'dashboard-crm'],
        'generators'         => ['generators.'],
        'clients'            => ['clients.'],
        'transporters'       => ['transporters.', 'transport-equipments.', 'waste-prices.'],
        'wastes'             => ['wastes.'],
        'final-destinations' => ['final-destinations.'],
        'manifests'          => ['manifests.'],
        'remisions'          => ['remisions.'],
        'withdrawals'        => ['withdrawals.', 'reports.withdrawals'],
        'users'              => ['users.'],
        'permissions'        => ['permissions.'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // SUPERADMIN siempre pasa
        if ($user?->role === 'SUPERADMIN') {
            return $next($request);
        }

        $pageKey = $this->resolvePageKey($request);

        // Rutas sin página mapeada no se restringen
        if ($pageKey === null) {
            return $next($request);
        }

        if (!RolePermission::hasPermission($user->role, $pageKey)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }
            return redirect()->route('pages-misc-not-authorized');
        }

        return $next($request);
    }

    private function resolvePageKey(Request $request): ?string
    {
        $routeName = $request->route()?->getName() ?? '';

        foreach (self::PAGE_ROUTE_MAP as $pageKey => $prefixes) {
            foreach ($prefixes as $prefix) {
                if ($routeName === $prefix || str_starts_with($routeName, $prefix)) {
                    return $pageKey;
                }
            }
        }

        return null;
    }
}
