# Speedweek Project Notes

## Project
Speedweek is a Laravel participant/admin portal for the Meet the Speed Speedweek event. It handles public registration, participant dashboards, finance/invoices, checklist items, motorcycle/tire/travel data, and a Filament admin panel.

## Tech Stack
- PHP 8.3, Laravel 13
- Filament admin panel at `/admin`
- SQLite by default in the current Docker image
- Vite, Tailwind CSS, Alpine.js
- PHPUnit via `php artisan test`

## Local Development
- Project root: `/home/speedweek`
- Local URL used during development: `http://speedweek.local`
- Local `.env` may differ from production and is not committed.
- The local demo data originally comes from `database/seeders/DatabaseSeeder.php`.
- Useful local commands:
  - `php artisan migrate --force`
  - `php artisan users:ensure-admin`
  - `php artisan optimize:clear`
  - `php artisan test`
  - `npm run build`

## Production / Deployment
- Production domain: `https://speedweek.bergmolen.nl`
- Production runs on Render behind a reverse proxy/load balancer.
- Laravel must trust forwarded headers so HTTPS is detected correctly.
- `APP_URL` on Render must be `https://speedweek.bergmolen.nl`.
- `ASSET_URL` should be empty or an HTTPS URL.
- Docker startup currently runs migrations, admin bootstrap, cache clears, then starts `php artisan serve`.
- If Render does not immediately show the newest code, wait for the deploy to finish and confirm the asset hash changed.

## Important URLs
- Public site: `https://speedweek.bergmolen.nl`
- Login: `https://speedweek.bergmolen.nl/login`
- Dashboard: `https://speedweek.bergmolen.nl/dashboard`
- Admin: `https://speedweek.bergmolen.nl/admin`

## Git Workflow
- Work on `main` unless instructed otherwise.
- Inspect `git status --short` before staging.
- Stage only files relevant to the task.
- Run relevant tests/build before committing.
- Push to `origin main` after a successful commit when asked to deploy/live push.

## Commit Messages
Use relevant, content-specific commit messages. Do not use vague messages like `update`, `fix`, `changes`, or `wip`.

Good examples:
- `Fix HTTPS asset URLs on Render`
- `Add onboarding data for new users`
- `Create lap times dashboard with demo sessions`
- `Document deployment and project workflow`

## Deploy / Cache Clear Workflow
After pushing deploy-related Laravel changes, clear caches on the target environment when possible:

```bash
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

Local verification can use the same commands. Render does not currently have a CLI configured in this workspace; if production commands are required, use Render shell/dashboard or add an approved deployment mechanism.

## Test / Build Commands
Run these before committing broad application changes:

```bash
php artisan test
npm run build
```

For focused changes, run the relevant feature test first, then the full suite if the change touches shared auth, dashboard, registration, or deployment behavior.

## Laravel / Filament Conventions
- Participant dashboard data starts from `User -> registrations() -> latest()`.
- `UserOnboardingService` creates idempotent baseline dashboard data for new users: event/package, registration, invoices, checklist items, motorcycle, tire request, and travel info.
- `Registration` automatically calculates totals and creates invoices in model events via `RegistrationPricing`.
- Filament access is controlled by `User::canAccessPanel()` and `is_admin`.
- Production admin user is bootstrapped by `php artisan users:ensure-admin` using `ADMIN_EMAIL`, `ADMIN_PASSWORD`, and `ADMIN_NAME`, with local demo fallbacks.
- Prefer route helpers and relative form actions; avoid hardcoded HTTP URLs.

## Known Pitfalls
- Production is behind Render/Cloudflare; without trusted proxy headers Laravel may generate `http://` form or asset URLs.
- `.env` changes are local only and are not pushed. Render environment variables must be updated separately.
- Local demo data can hide missing production bootstrap data. Do not rely on seeders for runtime production behavior.
- Curl with `-X POST -L` can incorrectly follow a redirect as POST and show a dashboard Method Not Allowed error; use browser testing or avoid forcing POST across redirects.
- Render may serve an older build until deployment completes.
- The sandbox can fail with `bwrap: loopback: Failed RTM_NEWADDR`; use approved/escalated commands when necessary.

## Rules for Future Codex Changes
- Read the relevant code before editing.
- Keep edits scoped to the requested behavior.
- Do not overwrite unrelated user changes.
- Use `rg` for search and inspect diffs before committing.
- Add or update tests for auth, dashboard, registration, onboarding, or deployment-sensitive changes.
- Run `php artisan test` and `npm run build` before commits that affect application behavior or frontend assets.
- Commit with a specific message that describes the actual change.
- Push only after tests/build are acceptable or after clearly documenting any verification that could not be run.
