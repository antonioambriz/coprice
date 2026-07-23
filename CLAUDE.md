# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Coprice is a Laravel 12 (PHP 8.2) server-rendered web app for managing waste-management logistics: generators (clients producing waste), transporters, waste catalogs, final destinations, manifests, remisiones, and a withdrawals ("retiros") log that is the operational core of the system. The UI is built on top of the **Vuexy** Bootstrap 5 admin template, with Blade views and jQuery/DataTables-driven pages rather than a SPA.

The repo is the full Vuexy template scaffold plus the real Coprice business app layered on top. Most of `app/Http/Controllers` (subfolders like `apps/`, `cards/`, `charts/`, `dashboard/`, `front_pages/`, `layouts/`, `wizard_example/`, etc.) and most of `routes/web.php` are template demo pages — unused by the real app but kept for reference/reuse. The actual business logic lives in the top-level controllers (`GeneratorController`, `ClientController`, `TransporterController`, `WasteController`, `FinalDestinationController`, `ManifestController`, `RemisionController`, `WithdrawalController`, `WastePriceController`, `TransportEquipmentController`, `UserController`, `RolePermissionController`, `ReportController`) and the route block in `routes/web.php` between the `Route::middleware('auth')->group(...)` opening (~line 186) and the `dashboard-analytics` legacy route reassignment around line 260 — everything after that point back into template demo routes.

## Development environment

This project runs via **Laravel Sail (Docker)**. Always prefix PHP/composer/artisan commands with `./vendor/bin/sail` — running `composer`, `artisan`, or `npm` directly against the host can break file permissions between the WSL/host filesystem and the Docker container's user.

```bash
./vendor/bin/sail up -d                       # start containers (app on :80, vite on :5173, mysql on :3306)
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm run dev                 # vite dev server (HMR)
./vendor/bin/sail npm run build               # production asset build
```

Container names are `coprice-laravel.test-1` (app) and `coprice-mysql-1` (db). If a container is "Up" but the site refuses connections, check `docker port coprice-laravel.test-1` — it should show `80/tcp -> 0.0.0.0:80`; if the port mapping is empty, `./vendor/bin/sail up -d` to recreate the container.

In production, the app also runs under Docker (no host PHP/Composer installed), so the equivalent commands need `docker exec coprice-laravel.test-1 ...` instead of Sail.

### Tests

```bash
./vendor/bin/sail artisan test                          # full suite
./vendor/bin/sail artisan test --filter=TestName         # single test
./vendor/bin/sail artisan test tests/Feature/SomeTest.php
```

Test suite is currently just framework boilerplate (`tests/Unit/ExampleTest.php`, `tests/Feature/ExampleTest.php`) — there is no real feature/unit test coverage of the business logic yet.

### Code style

`./vendor/bin/sail composer pint` (or `./vendor/bin/sail bin pint`) — Laravel Pint is installed as a dev dependency; no custom `pint.json` config exists, so it uses Pint's default Laravel ruleset.

## Architecture

### Permission system

Authorization is custom-built (not Spatie/permission package), split across:
- `App\Models\RolePermission` — defines the configurable roles (`FACTURACION`, `AMBIENTAL`, `CONSULTA`; `SUPERADMIN` is hardcoded and always has full access) and the page catalog (`RolePermission::PAGES`, keyed by `page_key`). Some pages are flagged `'system' => true` (e.g. `users`, `permissions`) and can only be toggled by SUPERADMIN — `RolePermissionController::update()` skips system pages when persisting non-superadmin role permissions.
- `App\Http\Middleware\CheckPermission` (aliased as `permission`) — resolves the current route name to a `page_key` via `CheckPermission::PAGE_ROUTE_MAP` (a prefix match against route names, e.g. `'transporters.'` covers `transporters.*` and `transport-equipments.*`, while `'clients.'` covers `clients.*` and `waste-prices.*`), then calls `RolePermission::hasPermission($user->role, $pageKey)`. Routes with no mapped page key are not restricted.
- `App\Http\Middleware\CheckRole` (aliased as `role:ROLE1,ROLE2`) — hard role gate, used e.g. for `role:SUPERADMIN` on `/permissions` and `/users`.

**When adding a new resource/route group**, add its route-name prefix to `CheckPermission::PAGE_ROUTE_MAP` and a matching entry in `RolePermission::PAGES` (with a label/icon), or it silently runs unrestricted for non-superadmins.

The sidebar (`resources/menu/verticalMenu.json`, rendered by `resources/views/layouts/sections/menu/verticalMenu.blade.php`) only hides items via a static `roles` array on the menu node (used for the whole "Configuración" header) — it does **not** consult `RolePermission` dynamically, so a role without `RolePermission` access to a page may still see it in the sidebar and get redirected to `pages-misc-not-authorized` on click.

### DataTables pattern

Catalog index pages (`generators`, `clients`, `transporters`, `wastes`, `final-destinations`, `manifests`, `remisions`, `users`) use **client-side** DataTables: the `get-data`/`getData` controller endpoint returns the entire collection as `{ data: [...] }`, and `resources/assets/js/{module}/index.js` initializes `new DataTable(...)` with no `serverSide` option (defaults to client-side paging/sorting/search). Do not set `serverSide: true` unless the controller is actually built to handle it — a prior bug had `serverSide: true` on a controller that just did `Model::all()`, which breaks pagination (`NaN` pages) and search ("filtrado" with 0 results) because Yajra's `draw`/`recordsTotal`/`recordsFiltered` contract was never implemented.

`withdrawals` (the retiros log) is the one exception, server-side via `yajra/laravel-datatables-oracle`, because that table is expected to grow large. `WithdrawalController::getData()` builds a query with explicit `leftJoin`s to `generators`, `sub_generators`, `transporters`, `manifests`, `users` and aliases columns (e.g. `generators.company_name as generator_name`). **Aliased columns cannot be referenced in a SQL `WHERE` clause** (MySQL only allows aliases in `ORDER BY`/`HAVING`), so every aliased/joined column that needs to be searchable has an explicit `->filterColumn('alias', fn ($query, $keyword) => $query->whereRaw('real_table.real_column like ?', [...]))` override — if you add a new joined/aliased column to that query, you must add a matching `filterColumn` or both column search and the global search box will throw or silently fail to filter on it.

### Domain model relationships

Core flow: a `Generator` (waste producer) optionally has `SubGenerator`s; a `Withdrawal` (retiro) is the record of waste picked up from a generator/sub-generator by a `Transporter` on behalf of a billed `Client` (`withdrawals.client_id`, required on new records), optionally tied to a `Manifest` or `Remision`, with line items in `WithdrawalItem`. `Client` is a separate commercial entity linked to generators/wastes/final-destinations via the `client_generator_wastes` pivot (`belongsToMany` with extra pivot columns, not a clean many-to-many). `WastePrice` holds per-client agreed pricing for wastes (`client_id` + `waste_id`, unique pair), managed from the client's edit page via `waste-prices.client`/`waste-prices.saveClient`. `TransportEquipment` (vehicles) belongs to a `Transporter` and is managed inline on the transporter's edit page (modal + nested AJAX CRUD under `transporters/{transporter}/equipments`), not its own top-level resource.

Migrations were consolidated into a single dated batch (`database/migrations/2026_05_29_*`) rather than the default Laravel per-feature timestamps — keep new migrations in that same later-dated sequence rather than backdating them.

### Frontend asset pipeline

`vite.config.js` auto-globs entry points — `resources/assets/js/**/*.js` is picked up entirely automatically (`glob.sync('resources/assets/js/**/*.js')`), as are vendor libs under `resources/assets/vendor/**`. **A new page script just needs to be created at `resources/assets/js/{module}/{view}.js` and referenced via `@vite([...])` in the Blade view's `@section('page-script')`** — no manual registration in `vite.config.js` is needed. Page-specific CSS overrides go in `@section('page-style')` (yielded by `resources/views/layouts/sections/styles.blade.php`), not inline `<style>` blocks outside that section.

`resources/assets/js/main.js` sets `DataTable.defaults.oLanguage` globally for Spanish DataTables text — don't duplicate per-page language config.

### Breadcrumbs

Catalog pages (generators, clients, transporters, wastes, final-destinations — the "Catálogos" sidebar group) use a fixed 3-level breadcrumb on index/create/edit: `Inicio → Catálogos → {ModelName}` (the model name does not change between index/create/edit, and the middle "Catálogos" crumb is plain text, not a link, since there is no catalogs-index route). The separator icon must be `breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2` — the `breadcrumb-icon icon-base` classes are required for `_breadcrumb.scss`'s divider color rule to apply; using a bare `ti tabler-chevron-right` renders an inconsistent-looking separator.
