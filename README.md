# Kemet Air — Domestic Flight Booking System (Laravel)

Full Blade-based flight booking system for Egyptian domestic routes: database
layer, branding, auth, bilingual (EN/AR) search and results, a complete
seat-selection → passenger-details → checkout booking flow, a customer
profile with printable tickets, and an admin dashboard with flight/booking
management. Drop these folders straight into a fresh Laravel project (they
mirror `app/`, `config/`, `database/`, `lang/`, `resources/views/`, `routes/`).

## Setup

1. Copy all folders into your Laravel app root, overwriting the default
   `database/migrations/xxxx_create_users_table.php` (this one adds `phone`
   and `role`).
2. Register the two middleware described below (**`SetLocale`** in the `web`
   group, **`admin`** as an alias) in `bootstrap/app.php` (Laravel 11+) or
   `app/Http/Kernel.php` (Laravel 10) — see the "Bilingual Support" section
   for exact snippets for both.
3. Run migrations and seed:
   ```bash
   php artisan migrate:fresh --seed
   ```
4. Default seeded logins (password for all: `password`):
   - Admin: `admin@flightbooking.eg`
   - Customer: `ahmed.mostafa@example.com`

## Schema overview

| Table              | Purpose                                                        |
|---------------------|----------------------------------------------------------------|
| users               | Admins and customers                                            |
| cities              | Egyptian cities (Cairo, Alexandria, Luxor, Aswan, Sharm, Hurghada) |
| airports            | One airport per city, linked by `city_id`                       |
| airlines            | EgyptAir, Air Cairo, Nile Air                                    |
| flights             | Routes between airports, price, seat counts, status              |
| seats               | Per-flight seat map (business rows first, then economy)          |
| discount_settings   | Group-discount tiers by passenger count                          |
| bookings            | One booking per purchase, holds pricing snapshot                 |
| passengers          | Each traveler on a booking, optionally tied to a seat            |
| payments            | One payment record per booking                                   |

## Foreign key / cascade decisions

- `airports.city_id` → cascade delete (an airport can't outlive its city).
- `flights.airline_id` → cascade delete.
- `flights.departure_airport_id` / `arrival_airport_id` → **restrict**
  (prevents deleting an airport that still has flights referencing it).
- `seats.flight_id` → cascade delete (a flight's seat map goes with it).
- `bookings.user_id` → cascade delete; `bookings.flight_id` → **restrict**
  (protects historical flight data while bookings exist against it).
- `passengers.booking_id` → cascade delete.
- `passengers.seat_id` → **set null** (removing a seat record doesn't wipe
  the passenger's booking history) and is `unique` so one seat can never be
  double-assigned.
- `payments.booking_id` → cascade delete.

## Discount logic

`DiscountSetting::getDiscountForPassengers(int $count)` looks up the
active tier matching the passenger count and returns a percentage.
`Booking::calculatePricing($pricePerSeat, $passengerCount)` uses it to
compute `subtotal`, `discount_amount`, and `final_price` — call this from
your booking controller before creating the `Booking` record.

Default seeded tiers:

| Passengers | Discount |
|------------|----------|
| 1–2        | 0%       |
| 3–4        | 5%       |
| 5–6        | 10%      |
| 7+         | 15%      |

## Seat map convention

Each flight gets 60 seats: rows 1–2 are business class (A/B/C/D, 8 seats),
rows 3–15 are economy (A/B/C/D, 52 seats). Adjust
`FlightSeeder::BUSINESS_ROWS` / `ECONOMY_ROWS` to change the layout.

## Color palette (for the UI phase)

- Primary Navy Blue `#1E3A8A`
- Secondary Sky Blue `#0284C7`
- Accent Amber/Gold `#F59E0B`
- Background `#F8FAFC`
- Dark Text `#0F172A`

---

## Step 2 — Layout, styling, navigation & authentication

Adds `resources/views/layouts`, `resources/views/auth`, `resources/views/home.blade.php`,
`app/Http/Controllers`, `app/Http/Requests/Auth`, `app/Http/Middleware`, and `routes/web.php`.

### What's included

- **`layouts/app.blade.php`** — Tailwind (CDN, no build step) configured with the brand's
  navy/sky/amber palette as named colors (`bg-navy`, `text-sky`, `bg-amber`, etc.), Google
  Fonts "Cairo" (display) + "Inter" (body), Alpine.js for the mobile menu and dropdowns,
  a flash-message region, and validation error summary.
- **Navigation** (`layouts/partials/navigation.blade.php`) — responsive navbar with Home,
  Search Flights, My Bookings, Profile, a conditional Admin Dashboard link, and
  Login/Register or a profile dropdown, all driven by `@auth`/`@guest`/`isAdmin()`.
- **Footer** (`layouts/partials/footer.blade.php`) — quick links and branding.
- **`<x-alert>`** — anonymous Blade component for success/error/warning flashes.
- **Auth** — `AuthController` (login, register, logout) backed by `LoginRequest` and
  `RegisterRequest` form requests, plus `auth/login.blade.php` and `auth/register.blade.php`.
- **Home** — `HomeController` feeds the city list (for the search form) and per-city
  "from" pricing (computed from seeded flights) to `home.blade.php`, which has the hero,
  the floating search form, and a Popular Destinations grid for Cairo, Aswan,
  Sharm El-Sheikh, Hurghada and Alexandria.
- **Routes that were scaffolds in earlier phases are now fully implemented** —
  see Phase 3/4 below for `bookings.*` and `admin.*`.

### One manual step: register the `admin` middleware alias

Blade route registration references `middleware(['auth', 'admin'])`, so add the alias:

**Laravel 11+ (`bootstrap/app.php`):**
```php
->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})
```

**Laravel 10 (`app/Http/Kernel.php`):**
```php
protected $middlewareAliases = [
    // ...existing aliases
    'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
];
```

### Design notes

- Brand name **"Kemet Air"** ("Kemet" — the ancient Egyptian name for Egypt
  itself) with a minimal falcon-wing mark, a nod to Horus imagery.
- Headings use Google Font **Cairo** — a literal, deliberate nod to the product's home
  city — paired with **Inter** for body/UI text.
- The hero uses an animated dashed flight path (a single motion moment, not scattered
  hover effects) over a navy-to-sky gradient with a desert-skyline silhouette.
- Destination cards use hand-drawn inline SVG motifs (pyramid, felucca sail, reef,
  windsurfer, lighthouse/corniche) instead of stock photography, so there are no
  broken image placeholders and the icons tie directly to each city's identity.

---

## Phase 2 — Flight Search & Results

Adds `FlightController::search()`, `FlightSearchRequest`, `resources/views/flights/`,
and updates `HomeController` (airport-based dropdowns) and `BookingController` (a
`create()` stub so "Book Now" has a real, auth-protected target).

### Search parameters

`GET /flights/search` (route name `flights.search`) accepts:

| Param | Required | Notes |
|---|---|---|
| `origin_airport_id` | yes | Must exist in `airports` |
| `destination_airport_id` | yes | Must exist and differ from origin |
| `departure_date` | yes | `Y-m-d`, today or later |
| `return_date` | no | If given, a second "return" result set is searched in the reverse direction |
| `passengers_count` | yes | 1–9 |
| `class_type` | no | `economy` \| `business` \| omitted (any) |
| `min_price`, `max_price`, `departure_window[]`, `airline_id[]` | no | Sidebar filters, applied on top of the base search |

### Design decisions worth knowing

- **Airport-based search.** The Step 1/2 home page originally searched by city
  code; it now searches by `airport_id` to match this phase's spec exactly.
  `HomeController` fetches `Airport::with('city')` for the dropdowns.
- **Per-class pricing without changing the schema.** `flights.price` is a single
  base fare in the DB. Rather than altering the Step 1 migrations, `Flight::
  CLASS_PRICE_MULTIPLIERS` (economy ×1.0, business ×1.6) computes a business fare
  at the application layer via `Flight::priceForClass()`. Swap this for real
  per-class pricing later without touching the search logic.
- **Seat capacity is checked per class**, not just against `flights.available_seats`:
  `Flight::availableSeatsForClass()` counts free rows in the `seats` table, so a
  flight with 0 business seats left but plenty of economy still appears (with an
  Economy-only fare card).
- **Group discounts are already applied on the results page** — each fare card
  shows the discounted total for the searched `passengers_count` via the existing
  `Booking::calculatePricing()`, with the pre-discount price struck through.
- **No pagination.** With a small seeded flight set per route/day, results are
  fetched and filtered in one pass; add `paginate()` in `FlightController::
  buildResults()` if the dataset grows.
- **Sidebar filter options stay stable.** The airline checklist and price bounds
  are computed from the route + date only (ignoring currently-applied filters),
  so options don't disappear as soon as you use them.
- **"Book Now" is a scaffold.** It resolves to `bookings.create` (auth-protected —
  guests are redirected to log in and returned via Laravel's `redirect()->intended()`,
  already wired in `AuthController`), carrying `flight_id`, `seat_class`, and
  `passengers_count`. The actual passenger form, seat map, and payment step are
  built in the next phase.

---

## Bilingual Support (English / Arabic)

Adds `config/locales.php`, `app/Http/Middleware/SetLocale.php`,
`LanguageController`, the `lang.switch` route, `lang/en/*.php` +
`lang/ar/*.php` (10 files each, every key present in both — verified), and
retrofits every existing view to use `__()` / `trans_choice()` instead of
hardcoded English.

### One manual step: register the `SetLocale` middleware globally

It must run on every web request (it reads the session and calls
`App::setLocale()` before any view renders), so add it to the **web**
middleware group — not as an alias like `admin`:

**Laravel 11+ (`bootstrap/app.php`):**
```php
->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})
```

**Laravel 10 (`app/Http/Kernel.php`):**
```php
protected $middlewareGroups = [
    'web' => [
        // ...existing middleware
        \App\Http\Middleware\SetLocale::class,
    ],
];
```

### How it works

- The navbar (desktop + mobile) shows an **EN / AR** pill switcher
  (`layouts/partials/language-switcher.blade.php`), linking to
  `route('lang.switch', 'ar')` / `'en'`.
- `LanguageController::switch()` stores the choice in `session('locale')` and
  redirects back — no page-specific logic needed.
- `SetLocale` reads that session value on every request and calls
  `App::setLocale()`, falling back to `config('app.locale')` if nothing is
  stored or the value is invalid.
- `layouts/app.blade.php` sets `<html lang="{{ app()->getLocale() }}" dir="...">`
  dynamically from `config('locales.available.*.dir')`, so **RTL is automatic**
  for Arabic — no separate RTL stylesheet.
- **Fonts:** "Cairo" (already used for all headings) was designed with Arabic
  *and* Latin glyphs together, so headings never need to switch fonts. Body
  text swaps from Inter (no Arabic glyphs at all) to **Tajawal** when the
  locale is Arabic.
- **RTL-safe classes throughout:** logical Tailwind utilities (`ms-*`/`me-*`,
  `ps-*`/`pe-*`, `start-*`/`end-*`, `text-start`) are used instead of
  `ml-*`/`mr-*`/`left-*`/`right-*`/`text-left` wherever direction matters, so
  spacing and alignment flip automatically. A small `.rtl-flip` utility class
  (in `layouts/app.blade.php`) mirrors directional icons (swap arrows, the
  route arrow between two airport codes) in RTL.
- **Latin data stays LTR even in Arabic UI** — email, phone, password, seat
  codes, flight numbers, and booking references are wrapped in `dir="ltr"`
  and `text-start`, since flipping digits/Latin text would make them
  unreadable (standard practice, same as real Arabic airline sites).
- **Arabic pluralization**, not just string swaps: `trans_choice()` is used
  for passenger counts and "flights found" messages, and `lang/ar/*.php`
  defines Arabic's actual plural ranges (1 / 2 / 3–10 / 11+) rather than
  reusing English's singular/plural split.
- Translation files are split by concern: `nav`, `footer`, `home`, `search`
  (shared by the homepage and results-page search bars), `flights` (results,
  cards, filters), `auth`, `booking` (the whole seats → passengers →
  checkout → ticket flow), `profile`, `admin`, and `common`.

---

## Phase 3 — Seat Selection & Passenger Flow

A session-backed, multi-step wizard (`BookingController`) — no API, just
GET/POST + redirects, consistent with the rest of the app:

`bookings.create` (from a fare's "Book Now") → `bookings.seats` (GET/POST) →
`bookings.passengers` (GET/POST) → `bookings.checkout` (GET) →
`bookings.confirm` (POST) → `bookings.confirmation` (ticket view).

### Design decisions worth knowing

- **Nothing from the client is trusted at confirm time.** The session draft
  only ever stores `flight_id`, `seat_class`, `passengers_count`, `seat_ids`,
  and `passenger` data — price and seat availability are always **recomputed
  from the database** in `confirm()`, inside a transaction with
  `lockForUpdate()` on the flight and the chosen seats, so two people can't
  double-book the same seat in a race. If a conflict is detected, the user is
  bounced back to seat selection with a friendly message.
- **The seat map** (`bookings/seats.blade.php`) groups seats by row (parsed
  from `seat_number`, e.g. `A1` → row 1), labels the Business/Economy cabin
  boundary, and uses native checkboxes bound to Alpine (`x-model="selected"`)
  so selection works with plain HTML semantics — Alpine only enforces the
  max-count and drives the "X of Y selected" counter and disabled states via
  Tailwind's `peer-*` selectors (no per-seat JS classes needed).
- **Seats are assigned to passengers in order** (sorted by `seat_number`) —
  there's no drag-and-drop reassignment UI; this keeps the flow linear and
  matches how most budget-airline booking flows work in practice.
- **Passenger validation matches the `passengers` table exactly**:
  `full_name`, `national_id_or_passport`, `date_of_birth` (must be in the
  past), `gender` (enum).
- **Payment is explicitly simulated** — `payment_method` is collected and a
  `Payment` row is created with `status = completed` immediately; there's no
  real gateway integration, and the checkout page says so
  (`booking.payment_simulated_note`).
- Every step guards against being visited out of order (`requireDraft()`),
  redirecting back to whichever earlier step is missing, with an explanatory
  flash message.

---

## Phase 4 — Profile & Admin Dashboard

### Customer profile (`ProfileController`, `profile/show.blade.php`)

Account details (name/email/phone/member-since) plus the 5 most recent
bookings, each linking to `bookings.show`.

### Printable / downloadable ticket (`bookings/ticket.blade.php`)

Shared by `bookings.show` (owner), `bookings.confirmation` (right after
paying), and `admin.bookings.show` (read-only, any booking). It's a
boarding-pass-style card with a **Print ticket** button
(`onclick="window.print()"`) — printing to PDF is the "download" path here,
since browsers' native print dialog already offers "Save as PDF" and this
avoids adding a PDF-generation package (e.g. `barryvdh/laravel-dompdf`) for a
demo project. The navbar and footer are hidden on print via Tailwind's
`print:hidden` variant. If you later want a real one-click PDF download
without the print dialog, that package is the natural next step.

### Admin dashboard (`Admin\DashboardController`, `admin/dashboard.blade.php`)

Live counts (total/upcoming flights, total/confirmed bookings, paid revenue,
registered customers) and the 8 most recent bookings.

### Admin flight management (`Admin\FlightController`, `admin/flights/*`)

Standard resourceful CRUD (`Route::resource(...)->except(['show'])`) with one
important constraint: **`total_seats` can only be set on creation.**
`Flight::generateSeatMap()` (added to the `Flight` model) builds the seat
rows immediately after a flight is created, using the same convention as
`FlightSeeder` (first 2 rows Business, rest Economy, 4 seats/row — configurable
via the method's `$businessRows` argument). Since real seat rows now exist
and may already be booked, **editing no longer touches seat count**, and
**deleting a flight is blocked** (with a translated error) if it has any
bookings — mirroring the `bookings.flight_id` `onDelete('restrict')`
constraint from the Step 1 schema, just with a friendlier message than a raw
DB error.

### Admin bookings oversight (`Admin\BookingController`, `admin/bookings/index.blade.php`)

A paginated table of every booking across all customers, linking to the
shared ticket view in read-only mode.

### Admin layout (`admin/layout.blade.php`)

A navy sidebar (Dashboard / Flights / Bookings) wrapping all admin pages —
per the original brand spec ("Admin Sidebar" in Primary Navy). Implemented as
a nested `@extends`: `admin/layout.blade.php` itself `@extends('layouts.app')`,
so the public navbar/footer/flash-messages/RTL handling all still apply
inside the admin area for free.
