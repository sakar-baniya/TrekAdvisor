# TrekAdvisor Code Architecture Blueprint

## 1) Current State Summary

The project already has useful role separation, but complexity comes from mixing multiple organizing styles at once:

1. Controller organization is by role in some places and by domain in others.
2. Route file is large and carries many aliases due to duplicate controller names across namespaces.
3. Some services are domain-focused, while others are cross-domain utility classes.
4. Views are split across many folders with a mix of role and page naming.

Main hotspots (largest files) are currently:

1. `app/Http/Controllers/Customer/AccountBookingsController.php`
2. `app/Http/Controllers/Customer/ProfileController.php`
3. `app/Services/Dashboard/DashboardNavigation.php`
4. `app/Services/Authorization/AuthorizationHelper.php`

## 2) Why Naming Feels Confusing

Primary reasons:

1. Same resource names under many namespaces (`HotelController`, `BookingController`, `DashboardController`, etc.).
2. Route aliases in `routes/web.php` hide intent and increase cognitive load.
3. Domain terms and role terms are mixed in the same layer.

## 3) Target Architecture (Simple, Ideal Laravel-First)

Use a feature/domain-first structure under `app/`, while keeping role-based authorization in policies/middleware.

Target shape:

```text
app/
	Domain/
		Booking/
			Actions/
			Services/
			Policies/
		Hotel/
			Actions/
			Services/
			Policies/
		Trek/
			Actions/
			Services/
			Policies/
		Payment/
			Services/
		User/
			Services/
			Policies/
	Http/
		Controllers/
			Web/
				BookingController.php
				HotelController.php
				TrekController.php
				ProfileController.php
			Admin/
			Staff/
			Owner/
			Api/V1/
		Requests/
		Middleware/
	Models/
	Providers/
```

Practical naming rules:

1. Avoid class aliases in routes when possible.
2. Use suffixes for role-scoped handlers when needed (`AdminHotelController`, `StaffDepartureController`).
3. Keep one clear meaning per class name.

## 4) Route Organization Blueprint

Split `routes/web.php` into focused files and include them in bootstrap routing:

1. `routes/web/public.php`
2. `routes/web/authenticated.php`
3. `routes/web/admin.php`
4. `routes/web/staff.php`
5. `routes/web/owner.php`
6. `routes/web/customer.php`

Benefits:

1. Smaller files.
2. Lower alias pressure.
3. Faster onboarding.

## 5) Service Layer Blueprint

Keep services, but make them predictable:

1. Domain services only (`Booking`, `Hotel`, `Trek`, `Payment`, `User`).
2. Move cross-cutting helper logic into dedicated utility namespaces (`Support` or `Shared`).
3. Keep service methods narrow: one use-case per method.

## 6) Controller Blueprint

Controllers should do only:

1. Request validation delegation.
2. Authorization check.
3. Service call.
4. Response mapping.

Move heavy querying/business conditions into services or dedicated query objects.

## 7) View Layer Blueprint

Use one consistent convention:

1. Keep domain folders (`bookings`, `hotels`, `treks`, `profile`, `payments`).
2. Keep role-only folders for role-only screens (`admin`, `staff`, `owner`).
3. Avoid duplicate concepts in both role and domain folders unless needed.

## 8) Phased Migration Plan (Safe)

### Phase A: Naming and File Boundaries

1. Rename duplicate role controllers to explicit names (reduce aliases).
2. Keep behavior unchanged.
3. Update imports/routes only.

### Phase B: Route Decomposition

1. Split web routes by role/domain.
2. Keep same route names/URLs.
3. Run route:list and smoke tests.

### Phase C: Service Consolidation

1. Group services by feature domain.
2. Reduce generic helpers with unclear ownership.
3. Add small unit tests for moved logic.

### Phase D: View Cleanup

1. Unify naming and folder conventions.
2. Remove compatibility wrappers once unused.

## 9) Immediate Quick Wins

1. Keep root minimal and archive non-runtime docs under `docs/archive/`.
2. Resolve currently staged unexpected file deletions before next structural refactor.
3. Add a short `CONTRIBUTING.md` naming convention section.

## 10) Definition of Done for "Clean Architecture" in this Repo

1. No confusing controller aliases required in route files.
2. Controllers under 100 lines for most classes.
3. Route files split by concern.
4. Service classes grouped by domain with predictable names.
5. New contributor can locate any feature in under 30 seconds.

