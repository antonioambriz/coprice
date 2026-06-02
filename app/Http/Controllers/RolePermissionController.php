<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $pages = RolePermission::PAGES;
        $roles = RolePermission::ROLES;

        // Permisos actuales indexados como [role][page_key] = true
        $permissions = RolePermission::all()
            ->groupBy('role')
            ->map(fn($rows) => $rows->pluck('page_key')->flip()->map(fn() => true)->toArray())
            ->toArray();

        return view('content.configuration.permissions', compact('pages', 'roles', 'permissions'));
    }

    public function update(Request $request)
    {
        $input = $request->input('permissions', []);
        $validRoles = array_keys(RolePermission::ROLES);
        $validPages = array_keys(RolePermission::PAGES);

        // Solo se gestionan los roles configurables (no SUPERADMIN)
        RolePermission::whereIn('role', $validRoles)->delete();

        $rows = [];
        $now  = now();

        foreach ($validRoles as $role) {
            foreach ($validPages as $pageKey) {
                // Las páginas del sistema solo las puede habilitar SUPERADMIN (hardcodeado)
                if (RolePermission::PAGES[$pageKey]['system']) {
                    continue;
                }
                if (!empty($input[$role][$pageKey])) {
                    $rows[] = [
                        'role'       => $role,
                        'page_key'   => $pageKey,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if ($rows) {
            RolePermission::insert($rows);
        }

        RolePermission::clearCache();

        return response()->json(['message' => 'Permisos actualizados correctamente.']);
    }
}
