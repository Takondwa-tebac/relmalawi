# REL Malawi: website + CMS

Public website for **Radio Entertainment Limited (REL), Malawi**, with a Filament admin panel that lets staff manage every piece of site content. The site started life as a Next.js UI; each page was ported to Vue and its hardcoded content moved into the database.

| Layer | Tech |
|---|---|
| Backend | Laravel 13, PHP 8.3+, SQLite by default |
| Public site | Inertia v3 + Vue 3 + Tailwind 4 (`resources/js`) |
| CMS / admin | Filament 5.9 at `/admin` |
| Media | spatie/laravel-medialibrary (images + conversions) |
| Roles | spatie/laravel-permission (`super-admin`, `editor`) |
| Auth | Laravel Fortify (login, 2FA, passkeys), reused for `/admin` |
| Typed routes | Laravel Wayfinder |
| Tests | Pest 4 |

## Quick start

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # tables + roles + all page content + demo images
php artisan storage:link          # needed for uploaded/seeded media URLs
composer dev                      # server + queue worker + vite (see `php artisan dev:list`)
```

- Public site: http://localhost:8000
- Admin panel: http://localhost:8000/admin (log in through the normal `/login`)

Local seed creates a convenience account `test@example.com` (factory default password) with the `super-admin` role. **Remove or change it before deploying.**

### Create your own admin

```bash
php artisan cms:make-admin owner@example.com                 # super-admin (prompts for name + password if new)
php artisan cms:make-admin writer@example.com --role=editor  # editor
```

## Commands cheat sheet

| Command | What it does |
|---|---|
| `composer dev` | Runs the dev processes (web server, queue worker, Vite) |
| `php artisan migrate --seed` | Create tables and seed roles, pages, campaigns, team, partners, steps, settings |
| `php artisan migrate:fresh --seed` | Wipe and rebuild the local database |
| `php artisan db:seed --class=RolesSeeder --force` | Re-sync roles/permissions after adding a resource (safe to re-run) |
| `php artisan db:seed --class="Database\Seeders\Content\HomeSeeder"` | Re-seed one content area (all live in `database/seeders/Content`) |
| `php artisan cms:make-admin {email} [--role=]` | Create or promote a CMS user |
| `php artisan storage:link` | Link `public/storage` (rerun if you move the project folder) |
| `php artisan wayfinder:generate --with-form` | Regenerate typed route/action helpers after changing routes or controllers (the `--with-form` flag matters: without it `vue-tsc` reports missing `.form` helpers; `npm run build` also regenerates them) |
| `php artisan queue:listen` | Process queued mail (contact notifications). Already part of `composer dev` |
| `npm run build` / `npm run dev` | Build / watch front-end assets |
| `npm run types:check` | `vue-tsc` type-check (two known errors in `UserInfo.vue` and `settings/Profile.vue` predate the CMS work) |
| `composer lint` / `composer lint:check` | Pint format / check |
| `composer types:check` | PHPStan (has a handful of known baseline errors, see Known issues) |
| `php artisan test --compact` | Full Pest suite (`composer test` also runs lint + PHPStan) |

## What is editable in the CMS

Every public page is rendered from the database. Nothing below needs a code change or deploy.

| Public page | Admin area (nav group) | Models |
|---|---|---|
| All pages: eyebrow, headline, accent, description, SEO | **Content → Pages** | `Page` |
| Home: campaign image roll | **Campaigns** (upload image, reorder, publish toggle) | `Campaign` (+ media) |
| Home: hero stat cards | **Stats** | `Stat` |
| Home: headline last line, second paragraph | **Site → Site settings** | `Setting` |
| About, Raffles, Technology, Regulation: cards and blocks | **Features** (filter by page/group, icon picker, reorder) | `Feature` |
| People: team grid | **Team members** (photo, reorder, publish) | `TeamMember` (+ media) |
| Partnerships: station/media logos | **Partners** (logo, type, reorder) | `Partner` (+ media) |
| How it works: numbered steps | **How it works steps** | `HowItWorksStep` |
| Contact: inbox of submissions | **Contact messages** (read/unread, reply by email) | `ContactMessage` |
| Banner, footer, contact email, marquee codes | **Site → Site settings** | `Setting` |
| Users and roles | **Users**, **Roles** | `User`, spatie `Role` |

Unpublished items never reach the public site. Images are optimised into conversions (`thumb`, `card`) automatically.

## Roles and permissions

- `super-admin`: everything, including users, roles and site settings.
- `editor`: manages content only (campaigns, stats, team, partners, features, steps, contact messages; pages are view/edit only). Cannot touch users, roles or site settings.
- Only users with one of these roles can enter `/admin`.
- All permissions come from one file, **`config/cms.php`**. To protect a new resource: add one line there, add a policy extending `App\Policies\ResourcePolicy`, then re-run `RolesSeeder`. Editors' permissions are reset to the config baseline whenever the seeder runs.
- Super-admins cannot delete themselves or remove their own role, and the built-in roles cannot be deleted.

## Contact form

`POST /contact` stores a `ContactMessage` and then, in the background:

- emails the sender a copy of their message (branded, with the REL logo),
- notifies every `super-admin` (Filament bell notification and email),
- emails the address configured as `contact_email` in Site settings.

Protection: invisible honeypot field, strict per-IP and per-email rate limits, and Google reCAPTCHA v3 score checking. See `.env.example` for the `RECAPTCHA_*` keys (create keys at https://www.google.com/recaptcha/admin, type **v3**). Mail needs a running queue worker (`composer dev` has one; in production run `php artisan queue:work` under a supervisor).

## Project map

```
app/
  Http/Controllers/Guest/     one invokable controller per public page
  Models/                     Page, Setting, Campaign, Stat, TeamMember, Partner,
                              Feature, HowItWorksStep, ContactMessage
  Filament/Admin/             Resources/<Name>/{Pages,Schemas,Tables}, Pages/ManageSiteSettings
  Policies/                   one policy per model (permissions from config/cms.php)
  Console/Commands/           cms:make-admin
config/cms.php                role/permission matrix
database/seeders/Content/     idempotent seeders per page group (auto-discovered by DatabaseSeeder)
resources/js/pages/           Vue pages (Welcome, About, ...)
resources/js/components/guest shared public-site components (NavBar, Footer, PageIntro, ...)
tests/Feature/                Pest tests per page, resource and the authorization matrix
```

How content reaches a page: `routes/web.php` -> `Guest\*Controller` -> reads models (`Page::intro('slug')` for the headline block) -> `inertia('PageName', props)` -> Vue page. Shared props (`site.*`: banner, footer, contact email, marquee) come from `HandleInertiaRequests` and the `Setting` model.

### Adding a new content type (checklist)

1. `php artisan make:model Thing -mf`, add `HasMedia` if it has images.
2. Create the Filament resource under `app/Filament/Admin/Resources/Things` (copy an existing one, e.g. `Partners`).
3. Add it to `config/cms.php` and create a `ThingPolicy`; run the `RolesSeeder`.
4. Pass the data from the relevant `Guest` controller (use an API Resource, eager load `media`).
5. Render it in the Vue page, add a seeder in `database/seeders/Content`, write a Pest test, run `wayfinder:generate`.

## Deployment notes

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=RolesSeeder --force     # roles + permissions
php artisan storage:link
php artisan cms:make-admin owner@yourdomain.com
npm ci && npm run build
php artisan optimize
```

Set `APP_URL` to the real public URL (media URLs are built from it), configure real `MAIL_*` settings and `QUEUE_CONNECTION`, and add the reCAPTCHA keys. Do not run the demo `DatabaseSeeder` in production unless you want the sample content and the test account.

## Known issues / notes

- `phpstan analyse` reports a few errors: some are old (Fortify, `config/boost.php`, `config/media-library.php`, `User::avatarUrl`), others come from library magic (Spatie conversions, Filament `$form`) or typing nits in new code.
- `vue-tsc` has two older errors (`UserInfo.vue`, `settings/Profile.vue`).
- The test suite raises PHP's memory limit (`phpunit.xml`) because image conversions run in tests. Running several test processes at once on Windows can fail with a compiled-view "Access is denied" race; rerun serially.
- Public registration (`/register`, from the starter kit) creates users with no role; they cannot enter `/admin`. Disable registration in `config/fortify.php` if you do not want it.
- Seeded content that is placeholder copy: the six "How it works" steps. Replace them in the CMS.
- `PagesFeaturesSeeder` keys rows by page, group and position; re-running it after editors reorder cards can create duplicates. Seed once, then edit in the CMS.
