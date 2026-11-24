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

3. Create an empty database, import `docs/schema.sql`, and (for a fresh install)
   add an admin user with a password hash:

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

## Known remaining work

The Sales workflow was already incomplete in CI3 (the list reads `tbl_sales`
while saves wrote `tbl_clients`) and is preserved as-is here. Finishing it,
adding pagination/roles, and adding automated tests are follow-up tasks.
