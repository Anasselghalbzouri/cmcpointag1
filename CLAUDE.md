# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**CMC Pointage** — a Laravel 13 attendance tracking system for a student residence (Cité des étudiants). It manages student entry/exit movements, room assignments, demandes (requests), and sanctions. The database is MySQL/MariaDB and must be seeded from `CMCpointage.sql` (not via Laravel migrations — the migration file only sets up a simplified schema).

## Commands

```bash
# Start all dev processes (server + queue + logs + Vite)
composer dev

# Or run individually
php artisan serve
npm run dev

# Run tests
composer test
# Or: php artisan test
# Single test: php artisan test --filter=TestClassName

# Code style
./vendor/bin/pint

# Initial setup (after cloning)
composer install
cp .env.example .env
php artisan key:generate
# Then import CMCpointage.sql into MySQL, configure DB in .env
# Visit /setup to verify
```

## Database

The live database schema comes from **`CMCpointage.sql`** (MariaDB dump), not from the Laravel migration. Key tables:

| Table | Purpose |
|---|---|
| `utilisateurs` | Staff/admin users (roles: `admin`, `security`, `etudiant`) |
| `etudiants` | Student residents (soft-deleted via `deleted_at`) |
| `pavillons` | Dormitory buildings (type: `femme`/`homme`) |
| `chambres` | Rooms with `capacite` and `occupants_actuels` counters |
| `mouvements` | Entry/exit log with `etudiant_id`, `pavillon_id`, `type` (`entree`/`sortie`), `date_heure` |
| `demandes` | Student requests with `statut` (`en_attente`, `approuvee`, `refusee`) |
| `sanctions` | Sanctions with `statut` (`active`, `levee`) |
| `visites` | Visitor records with `statut` (`entree`, `sortie`, `en_cours`) |
| `archive_mouvements` | Archived movement history |

Default admin credentials: `admin@cmc.ma` / `password`

## Architecture

**Controllers use raw DB queries** (`DB::table(...)`) rather than Eloquent models — the only Eloquent model with actual usage is `User` (mapped to `utilisateurs` table). All student/room/movement operations use the query builder directly.

**Role-based access** is enforced via `CheckRole` middleware (`app/Http/Middleware/CheckRole.php`), registered as `role` alias in `bootstrap/app.php`. Routes are guarded with `role:admin,security` — the `security` role redirects directly to `/pointage` on login.

**Dashboard routing** in `DashboardController::index()` branches by role: admin → stats view, security → redirect to pointage, student → student view.

**Pointage flow**: scanning a CIN (`cin` field on `etudiants`) auto-toggles entry/exit by checking the last `mouvements` record for that student. Movements are inserted with `pavillon_id` derived from the student's `chambre_id` → `chambres.pavillon_id` (falls back to the first pavillon if the student has no room assigned).

**Room occupancy** is maintained manually: `chambres.occupants_actuels` is incremented/decremented in `StudentController` whenever a student's room assignment changes.

**Academic year progression/archival**: `etudiants` has `duree_formation` (`2_ans`/`2_ans_demi`, set at creation) and `annee_etude` (`1`/`2`, system-managed). `date_sortie_prevue` is computed as `date_entree + 24 or 30 months` and is the single source of truth for when a student finishes. `App\Services\StudentAcademicYearService::process()` archives (`statut=archive` + soft-delete + free room) any active student whose `date_sortie_prevue` has passed, and promotes `annee_etude` 1→2 for active students past 12 months — archival is checked first so a student can't be promoted instead of archived in the same run. Triggered via an admin button on `/students` (`students.processAcademicYear` route, admin-only), the `php artisan students:process-academic-year` command, or the daily schedule in `bootstrap/app.php`.

## Important Discrepancy

The Laravel migration (`database/migrations/0001_01_01_000000_create_all_tables.php`) defines a simplified `users` + `movements` schema that does **not** match the actual production schema in `CMCpointage.sql`. Do not run `php artisan migrate` on the production database — import `CMCpointage.sql` directly instead.
