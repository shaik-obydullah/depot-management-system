# Depot Management System

A small stock/inventory/cashbook admin app, upgraded from CodeIgniter 3 to
**CodeIgniter 4.7.6**.

## Requirements

- PHP 8.2+ (target deployment: PHP 8.5) with `intl`, `mbstring`, `mysqli`
- MySQL/MariaDB
- Composer 2

## Installation

1. Install dependencies:

   ```bash
   composer install
   ```

2. Copy the environment template and fill in your settings:

   ```bash
   cp env .env
   ```

   Set `database.default.*`, `app.baseURL`, and generate an `encryption.key`:

   ```bash
   php -r 'echo bin2hex(random_bytes(16)), PHP_EOL;'
   ```

   For local development over plain HTTP, also set `cookie.secure = false` and
   `app.forceGlobalSecureRequests = false` in `.env`.

3. Create an empty database, then either run the CI4 migrations or import
   `docs/schema.sql`:

   ```bash
   php spark migrate
   ```

   For a fresh install, add an admin user with a password hash:

   ```bash
   php -r 'echo password_hash("change-me", PASSWORD_DEFAULT), PHP_EOL;'
   ```

   Existing CI3 databases need no data migration: legacy plaintext passwords
   are re-hashed automatically on the next successful login.

4. Point the web server document root at `public/`. With Apache, `public/.htaccess`
   provides clean URLs; with nginx, rewrite all requests to `public/index.php`.
   Make `writable/` writable by the web server.

5. Verify routing:

   ```bash
   php spark routes
   ```

## What changed in the CI4 upgrade

- Full PSR-4 port: `App\Controllers`, `App\Models`, `App\Filters`; CI3
  `application/` and root `system/` removed; framework now comes from Composer.
- Front controller moved to `public/index.php`; static assets live in
  `public/assets/`.
- All URLs are explicitly declared in `app/Config/Routes.php` (CI4 auto-routing
  is disabled). Write endpoints are POST-only.
- Auth/session timeout moved into `App\Filters\AuthFilter`; CSRF is enabled and
  every form includes `csrf_field()`. AJAX forms serialize the token via
  `$('#form').serialize()`.
- DB credentials, base URL and encryption key come from `.env`, not the repo.
- Raw interpolated SQL replaced with Query Builder calls; inventory upserts now
  scope by product **and** branch; stock writes run in a DB transaction.
- Output is escaped with `esc()` in views; login uses `password_verify()` with a
  transparent upgrade path for legacy plaintext passwords.
- Schema is committed as `docs/schema.sql` (structure only, InnoDB); the old
  phpMyAdmin dump with sample data is git-ignored.

## Follow-ups completed after the upgrade

- **Sales workflow:** `tbl_sales` plus `sale_details` are now written in one
  transaction, with create/edit/view screens and per-line product snapshots.
  Stock and cashbook movements are intentionally unaffected, matching the
  legacy design.
- **Pagination:** products, branches, dealers, stocks, inventory, cashbook and
  sales lists use CI4's pager (10 rows per page), rendered by the shared layout.
- **Roles:** product/branch/dealer write routes require `superadmin`; stock and
  sales remain available to both roles. The role is stored in the session at
  login, enforced by `App\Filters\RolesFilter`, and used to hide master-data
  links/buttons. Non-authorized AJAX writes receive HTTP 403.
- **Migrations:** `2026-11-24-100000_CreateDepotSchema.php` creates the same
  structure as `docs/schema.sql`. It uses `CREATE TABLE IF NOT EXISTS`, so it is
  safe to run once against a database imported from the SQL file.
- **Tests/CI:** `vendor/bin/phpunit` covers app boot, role policy and sale
  totals; `.github/workflows/ci.yml` runs lint-free syntax/route checks and the
  suite on PHP 8.5 for pushes and pull requests to `main`.

## Testing

```bash
vendor/bin/phpunit
php spark routes
```

Database-dependent behaviour is verified manually with a disposable MariaDB
container; see `docs/agents.md` for the conventions used by that flow.
