# Agent Notes

Guidance for automated agents and contributors working on this repository.

## Stack and layout

- CodeIgniter **4.7.6**, installed via Composer. Target runtime is PHP **8.5**
  (`composer.json` allows 8.2+); required extensions: `intl`, `mbstring`, `mysqli`.
- Document root is `public/`. Static assets live in `public/assets/`.
- Application code is PSR-4 under `App\` (`app/Controllers`, `app/Models`,
  `app/Filters`, `app/Views`). Config classes live under `Config\`.
- Database settings, base URL, and encryption key come from `.env`; only the
  template `env` is committed. Never commit real credentials.
- `docs/schema.sql` is the structure-only reference schema.
  `docs/db_depot.sql` contains legacy sample data and is git-ignored.

## Useful commands

```bash
composer install
php spark routes
php spark migrate
vendor/bin/phpunit
```

If the host lacks PHP/Composer, use a PHP 8.2+ CLI container with `intl`,
`mbstring`, and `mysqli` enabled, mounting the repo at `/app`.

## Conventions

- All URLs are declared explicitly in `app/Config/Routes.php`; auto-routing is
  disabled. Write endpoints are POST-only.
- CSRF is enabled globally. Every form must include `<?= csrf_field() ?>` and
  AJAX forms must submit the serialized form so the token is included.
- Escape all echoed values in views with `esc()`.
- Use Query Builder or model methods instead of interpolated raw SQL.
- Multi-table writes (stock, sales, inventory, cashbook) must run inside a DB
  transaction.
- Authentication and the 15-minute inactivity timeout live in
  `App\Filters\AuthFilter`. Store `user_id`, `user_name`, `user_type`, and
  `login_time` in the session on login.
- Master-data changes (products, branches, dealers) are restricted to the
  `superadmin` role; daily stock/sales operations are available to both roles.
- Keep `vendor/`, `writable/logs/*`, `writable/session/*`, and
  `writable/debugbar/*.json` out of commits.

## Working style

- Prefer small, focused changes and validate them before committing.
- Preserve legacy behavior unless the task explicitly asks to change it; the
  Sales workflow was historically incomplete and is being completed deliberately.
- After changes, run syntax lint, `php spark routes`, and the test suite. For
  database-dependent behavior, use a disposable MariaDB container and the
  committed schema/migrations.
- Report any remaining follow-up work in `README.md` rather than leaving it
  implicit.
