# Repo Cleanup Audit (2026-04-14)

## Scope and safety
- Existing codebase audit only. No behavior-changing deletions were made.
- If usage confidence is low, item is marked as review-needed.

## 1) Empty directories
### Found in project code folders
- None found after excluding dependency and runtime folders (`vendor`, `node_modules`, `storage/framework`, `storage/logs`, `.git`).

### Found in dependency folders (do not edit manually)
- `node_modules/.vite-temp`
- `node_modules/@tailwindcss`
- `vendor/symfony/mime/Resources/bin`
- `vendor/symfony/string/Resources/bin`

Rationale:
- These folders are dependency artifacts and may be recreated by installs/build tools.

## 2) Duplicate files (exact same content)
### High-confidence duplicates
1. `public/css/app.css` and `resources/css/legacy/app.css`
- Exact same hash.
- Rationale: One appears to be built/public output while the other is a legacy source copy.
- Action: Keep one source of truth. If `resources/css/legacy/app.css` is no longer part of asset flow, remove it.

2. `resources/views/customer/customer-dashboard.blade.php` and `resources/views/customer/dashboard.blade.php`
- Exact same hash.
- Rationale: Route/controller currently returns `customer.customer-dashboard`.
- Action: `resources/views/customer/dashboard.blade.php` is a strong unused-file candidate.

3. `public/favicon.ico` and `resources/views/payments/esewa-redirect.blade.php`
- Both are empty files (same empty hash).
- Rationale: `resources/views/payments/esewa-redirect.blade.php` is empty and no references found.
- Action: Safe candidate for deletion: `resources/views/payments/esewa-redirect.blade.php`.

## 3) Likely unused code/files (references audit)
### Strong candidates
1. `resources/views/customer/dashboard.blade.php`
- No controller route/view call found for `view('customer.dashboard')`.
- Active controller uses `view('customer.customer-dashboard')`.

2. `resources/views/payments/esewa-redirect.blade.php`
- Empty file.
- eSewa flow uses `view('bookings.esewa-redirect')` in `app/Http/Controllers/EsewaCheckoutController.php`.

### Dead method paths (not routed)
1. `app/Http/Controllers/Customer/DashboardController.php`
- Methods:
  - `staff()`
  - `hotelOwner(Request $request)`
- No route usage found in `routes/web.php` or `routes/api.php`.
- Action: mark as dead-path candidates; remove only after one full route review.

## 4) Overlapping functionality
1. Staff dashboard controller overlap
- `app/Http/Controllers/Customer/DashboardController.php` contains `staff()` dashboard rendering.
- `app/Http/Controllers/Staff/DashboardController.php` already owns staff dashboard route.
- Rationale: This is overlapping responsibility and makes ownership unclear.
- Action: keep only role-specific dashboard controller ownership.

## 5) Dependency audit
## PHP dependencies (`composer.json`)
- `stripe/stripe-php` is actively used (Stripe service classes and payment flow references found).
- `laravel/pail` is used in composer `dev` script (`php artisan pail --timeout=0`).
- `laravel/sail` has no first-party code/script reference found.
  - Status: review-needed (likely optional local tooling).
- `laravel/breeze` has no runtime references (expected for scaffolding package).
  - Status: not a runtime concern, but removable if scaffolding updates are finished.

## Node dependencies (`package.json`)
- `package.json` currently has no `scripts` and no dependency list.
- `npm run build` fails with "Missing script: build".
- Rationale: frontend build pipeline is not declared in package manifest.
- Action: add scripts/dependencies if frontend build is expected; otherwise remove stale `node_modules` from repo workflows.

## 6) Tests/build safety check
### Tests
- Command run: `php artisan test`
- Result: failing (not caused by audit refactor).
- Root issue shown: SQLite test DB fails on MySQL-style `ALTER TABLE ... MODIFY ...` in migrations.
- Action: make test-safe migration branch for SQLite or avoid `MODIFY` for sqlite connections.

### Build
- Command run: `npm run build`
- Result: failed (`Missing script: build`).
- Action: define scripts in `package.json` if JS/CSS build is part of CI.

## 7) Suggested cleanup plan (safe order)
1. Delete unused empty view file:
- `resources/views/payments/esewa-redirect.blade.php`

2. Keep one customer dashboard view file:
- Remove `resources/views/customer/dashboard.blade.php` after quick QA.

3. Decide source-of-truth for duplicate CSS:
- Keep either `resources/css/legacy/app.css` or generated `public/css/app.css` per your pipeline.

4. Review dead methods in customer dashboard controller:
- `staff()` and `hotelOwner()` in `app/Http/Controllers/Customer/DashboardController.php`.

5. Tooling cleanup review:
- `laravel/sail` in `composer.json` and frontend scripts in `package.json`.
