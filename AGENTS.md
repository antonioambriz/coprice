# AGENTS

## Purpose
This file helps AI coding agents understand the codebase and act consistently in the `coprice` repository. It is intended for Claude-style and other AI assistants working on this Laravel + Vite project.

## Project overview
- Laravel 12 application running on PHP 8.2.
- Frontend assets are built with Vite and use a Vuexy-style admin dashboard UI.
- The repo mixes server-rendered Laravel pages and a large frontend asset pipeline under `resources/assets`.
- Domain controllers and resources exist under `app/Http/Controllers`, with many UI sections organized by folder.

## Key commands
- `composer install`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan migrate`
- `composer dev` — starts the full local development environment, including `php artisan serve`, queue listener, `pail`, and `npm run dev`.
- `npm run dev` — builds and serves frontend assets via Vite.
- `npm run build` — builds production frontend assets.
- `php artisan test` or `./vendor/bin/phpunit` — run PHP tests.

## Important paths
- `app/Models` — main Eloquent models.
- `app/Http/Controllers` — primary controllers, including custom auth and domain controllers.
- `routes/web.php` — main route definitions and resource controllers.
- `resources/views` — Blade templates.
- `resources/assets` — static JS/CSS assets used by Vite.
- `resources/js/app.js` — JavaScript entry point.
- `vite.config.js` — Vite build configuration and asset collection.
- `config/app.php` — custom aliases and app settings.
- `app/Helpers/Helpers.php` — autoloaded helper functions available through `Helper` alias.

## Coding guidance for AI agents
- Preserve Laravel conventions and do not rework routing structure without explicit user direction.
- When modifying routes, update `routes/web.php` and corresponding controller methods consistently.
- For frontend changes, follow the existing Vite input pattern in `vite.config.js` and the admin theme structure under `resources/assets`.
- Prefer using existing controllers and view structure over introducing new ad hoc file locations.
- Keep translations together in `lang/*.json` and `lang/en/*`.
- Avoid changing project-wide configuration unless the user explicitly asks for config updates.

## Notes for Claude-style assistants
- Focus on concise, accurate edits.
- If you are uncertain about behavior, refer to `routes/web.php` and existing controllers before generating new routes or views.
- Do not assume full-stack Vue or SPA behavior; this repository is primarily a server-rendered Laravel app with Vite-managed assets.

## Useful references
- `README.md` — generic Laravel boilerplate documentation.
- `composer.json` — PHP requirements and `composer dev` setup.
- `package.json` / `vite.config.js` — frontend asset build and dev tooling.
- `phpunit.xml` — PHP test runner configuration.
