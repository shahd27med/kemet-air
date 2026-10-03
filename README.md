# Kemet Air — Domestic Flight Booking System

Kemet Air is a Blade-based domestic flight booking system for Egyptian routes.  
The project includes flight search, bilingual English/Arabic support, seat selection, passenger management, checkout with simulated payment, customer profiles and printable tickets, plus an admin dashboard for flight and booking management.

## Technology Stack

| Technology | Version / Usage |
|---|---|
| **Laravel** | 12.x |
| **PHP** | 8.2+ |
| **Database** | MySQL (current project configuration) |
| **Frontend** | Laravel Blade |
| **Tailwind CSS** | Loaded through CDN in the main application layout |
| **Alpine.js** | 3.14.1 via CDN for lightweight interactions |
| **Vite** | Configured in the project for Laravel assets |
| **Authentication** | Laravel session authentication |
| **Localization** | English (EN) / Arabic (AR) with automatic RTL support |

> **Important:** This project is built for **Laravel 12**. The middleware configuration uses the Laravel 11/12 `bootstrap/app.php` structure.

---

## Main Features

### Customer Features

- User registration and login.
- Logout and authenticated sessions.
- English / Arabic language switcher.
- Automatic RTL layout for Arabic.
- Flight search by:
  - Origin airport
  - Destination airport
  - Departure date
  - Optional return date
  - Number of passengers
  - Economy / Business class
- Additional search filters:
  - Price range
  - Departure time window
  - Airline
- Browse all upcoming scheduled flights.
- Group discounts based on passenger count.
- Multi-step booking flow:
  1. Select flight and fare.
  2. Select seats.
  3. Enter passenger details.
  4. Review checkout.
  5. Select a simulated payment method.
  6. Confirm booking.
  7. View the booking ticket.
- Customer profile.
- View previous bookings.
- Printable boarding-pass-style ticket.
- Contact and About pages.

### Admin Features

- Admin-only dashboard.
- Live dashboard statistics:
  - Total flights
  - Upcoming flights
  - Total bookings
  - Confirmed bookings
  - Paid revenue
  - Registered customers
- Flight management:
  - Create flights
  - Edit flights
  - Delete flights when allowed
- Automatic seat-map generation for newly created flights.
- View all customer bookings.
- View booking/ticket details.
- Admin sidebar layout.

---

## Project Setup

### Requirements

Before running the project, make sure you have:

- PHP **8.2 or newer**
- Composer
- MySQL
- A PHP/MySQL environment such as XAMPP, Laragon, or a similar local server
- Node.js and npm are optional for the current main UI because Tailwind CSS and Alpine.js are loaded through CDN.

### 1. Install PHP dependencies

From the project root:

```bash
composer install
```

### 2. Configure the environment

Create the `.env` file if it does not already exist:

```bash
cp .env.example .env
```

On Windows, you can also simply copy `.env.example` and rename the copy to `.env`.

Generate the application key:

```bash
php artisan key:generate
```

### 3. Configure MySQL

Create a MySQL database, for example:

```text
kemet_air
```

Then configure the database section in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kemet_air
DB_USERNAME=root
DB_PASSWORD=
```

Use your own MySQL username and password if they are different.

> The included `.env.example` follows Laravel's default environment template. For this project, configure the `.env` file for MySQL before running the migrations.

### 4. Run migrations and seed the database

For a fresh development database:

```bash
php artisan migrate:fresh --seed
```

This creates the application tables and inserts the demo data, including cities, airports, airlines, flights, seats, discount tiers, users, and sample bookings.

### 5. Start the Laravel server

```bash
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

or:

```text
http://localhost:8000
```

---

## Demo Accounts

All seeded demo accounts use:

```text
Password: password
```

### Admin

```text
Email: admin@flightbooking.eg
Role: admin
```

### Customer

```text
Email: shahd.ahmed@example.com
Role: customer
```

Other seeded customer accounts include:

```text
mona.youssef@example.com
karim.adel@example.com
sara.ibrahim@example.com
omar.hassan@example.com
```

All use the same demo password: `password`.

> These credentials are for local/demo development only. Change them before using the application in a real environment.

---

## Middleware Configuration

The required middleware is **already registered** in the current project.

`bootstrap/app.php` contains:

- `SetLocale` appended to the web middleware group.
- `admin` registered as an alias for `EnsureUserIsAdmin`.

Therefore, **no manual middleware registration is required** after cloning this project.

The configuration follows the Laravel 12 application bootstrap structure.

---

## Database Schema

The main application tables are:

| Table | Purpose |
|---|---|
| `users` | Admin and customer accounts |
| `cities` | Egyptian cities |
| `airports` | Airports associated with cities |
| `airlines` | Available airlines |
| `flights` | Flight routes, schedules, prices and seat counts |
| `seats` | Individual seats for each flight |
| `discount_settings` | Passenger-count discount tiers |
| `bookings` | Booking and pricing information |
| `passengers` | Passenger information for each booking |
| `payments` | Payment records |

### Foreign-key behavior

- `airports.city_id` → cascade delete.
- `flights.airline_id` → cascade delete.
- `flights.departure_airport_id` / `arrival_airport_id` → restrict delete.
- `seats.flight_id` → cascade delete.
- `bookings.user_id` → cascade delete.
- `bookings.flight_id` → restrict delete.
- `passengers.booking_id` → cascade delete.
- `passengers.seat_id` → set null and is unique.
- `payments.booking_id` → cascade delete.

The restrictions on flights and airports help protect existing booking and flight history.

---

## Flight and Seat Data

The `FlightSeeder` creates demo domestic routes involving:

- Cairo
- Alexandria
- Luxor
- Aswan
- Sharm El-Sheikh
- Hurghada

The seeded airlines are:

- EgyptAir
- Air Cairo
- Nile Air

### Seat Map

Each seeded flight contains **60 seats**:

- Rows 1–2: Business class
- Rows 3–15: Economy class
- 4 seats per row: A, B, C, D

So:

```text
Business:  2 × 4 = 8 seats
Economy:  13 × 4 = 52 seats
Total:     60 seats
```

New flights created through the admin dashboard also receive an automatically generated seat map.

---

## Discount Logic

Group discounts are based on the number of passengers:

| Passengers | Discount |
|---:|---:|
| 1–2 | 0% |
| 3–4 | 5% |
| 5–6 | 10% |
| 7+ | 15% |

The discount is calculated by:

```php
DiscountSetting::getDiscountForPassengers($count)
```

Final booking pricing is calculated through:

```php
Booking::calculatePricing($pricePerSeat, $passengerCount)
```

The calculation produces:

- Subtotal
- Discount amount
- Final price

The price is recalculated from the database during booking confirmation rather than trusting a client-supplied total.

---

## Flight Search

The main search endpoint is:

```text
GET /flights/search
```

Route name:

```text
flights.search
```

### Search Parameters

| Parameter | Required | Description |
|---|---|---|
| `origin_airport_id` | Yes | Departure airport |
| `destination_airport_id` | Yes | Arrival airport |
| `departure_date` | Yes | Departure date |
| `return_date` | No | Optional return date |
| `passengers_count` | Yes | Number of passengers, 1–9 |
| `class_type` | No | `economy`, `business`, or any |
| `min_price` | No | Minimum price filter |
| `max_price` | No | Maximum price filter |
| `departure_window[]` | No | Departure-time filters |
| `airline_id[]` | No | Airline filters |

Search is airport-based rather than city-code-based.

---

## Booking Flow

The booking process is session-backed and implemented using normal Laravel web routes.

```text
Flight Search
     ↓
Select Flight
     ↓
Seat Selection
     ↓
Passenger Details
     ↓
Checkout
     ↓
Simulated Payment
     ↓
Booking Confirmation
     ↓
Printable Ticket
```

Main route names include:

```text
bookings.create
bookings.seats
bookings.seats.store
bookings.passengers
bookings.passengers.store
bookings.checkout
bookings.confirm
bookings.confirmation
bookings.show
```

### Booking Safety

At confirmation time:

- Flight availability is checked again.
- Selected seats are checked again.
- Pricing is recalculated.
- Database transactions are used.
- Flight and selected-seat records are locked during confirmation.
- The application prevents two users from successfully booking the same seat.

---

## Payment

Payment is **simulated** for this project.

Supported payment methods are:

```text
credit_card
debit_card
wallet
cash
```

No real payment gateway is connected.

A successful simulated checkout creates a payment record with a completed status.

---

## Bilingual Support

The application supports:

- English (`en`)
- Arabic (`ar`)

The language switcher stores the selected language in the session.

Arabic automatically changes the document direction to:

```text
RTL
```

while English uses:

```text
LTR
```

The project also uses Arabic-aware typography and translations for the main application screens.

Translation files are located under:

```text
lang/en/
lang/ar/
```

---

## Frontend and Styling

The main application layout uses:

- Tailwind CSS through the CDN
- Alpine.js 3.14.1 through the CDN
- Google Fonts:
  - Cairo
  - Inter
  - Tajawal

The project also contains a Vite configuration and frontend package configuration. The current main application layout does **not** depend on a Vite build to load Tailwind CSS; the primary UI styling is loaded directly from the Tailwind CDN.

The main brand palette is:

| Color | Hex |
|---|---|
| Navy | `#1E3A8A` |
| Sky Blue | `#0284C7` |
| Amber / Gold | `#F59E0B` |
| Ice / Background | `#F8FAFC` |
| Dark Text | `#0F172A` |

---

## Application Structure

Important directories include:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── AuthController.php
│   │   ├── BookingController.php
│   │   ├── FlightController.php
│   │   ├── HomeController.php
│   │   ├── LanguageController.php
│   │   └── ProfileController.php
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   └── SetLocale.php
│   └── Requests/
├── Models/
└── Providers/

database/
├── migrations/
└── seeders/

lang/
├── ar/
└── en/

resources/
├── views/
│   ├── admin/
│   ├── auth/
│   ├── bookings/
│   ├── flights/
│   ├── layouts/
│   └── profile/
├── css/
└── js/

routes/
└── web.php

bootstrap/
└── app.php
```

---

## Main Routes

### Public

```text
GET  /
GET  /flights
GET  /flights/search
GET  /lang/{locale}
GET  /about
GET  /contact
POST /contact
```

### Authentication

```text
GET  /login
POST /login
GET  /register
POST /register
POST /logout
```

### Customer

```text
GET  /profile

GET  /bookings
GET  /bookings/create
GET  /bookings/seats
POST /bookings/seats
GET  /bookings/passengers
POST /bookings/passengers
GET  /bookings/checkout
POST /bookings/confirm
GET  /bookings/{booking}
GET  /bookings/{booking}/confirmation
```

### Admin

```text
GET    /admin/dashboard
GET    /admin/flights
POST   /admin/flights
GET    /admin/flights/{flight}/edit
PUT    /admin/flights/{flight}
DELETE /admin/flights/{flight}

GET    /admin/bookings
GET    /admin/bookings/{booking}
```

Admin routes require both:

```text
auth
admin
```

---

## Admin Flight Management

The admin flight section uses resourceful CRUD routes.

When creating a flight:

- Total seat count is defined.
- A corresponding seat map is generated automatically.
- Business and Economy seats are created according to the project's seat-map convention.

When editing a flight, the seat count is not changed because existing seats may already be booked.

A flight with existing bookings cannot be deleted.

---

## Customer Profile and Tickets

The profile page displays:

- Customer name
- Email
- Phone
- Membership date
- Recent bookings

The ticket view is shared by customer and admin booking pages.

The ticket includes a **Print ticket** action using the browser's print dialog.

For example, a user can choose:

```text
Print → Save as PDF
```

No external PDF-generation package is required for the current implementation.

---

## Contact Page

The contact form validates:

- Name
- Email
- Subject
- Message

The current implementation validates the submitted information and displays a success message. It does **not** send an actual email.

---

## Testing

Laravel's standard test command can be run with:

```bash
php artisan test
```

The project currently contains the Laravel example feature and unit tests.

---

## Useful Artisan Commands

Clear cached configuration:

```bash
php artisan config:clear
```

Clear application cache:

```bash
php artisan cache:clear
```

View registered routes:

```bash
php artisan route:list
```

Refresh the database and reload all demo data:

```bash
php artisan migrate:fresh --seed
```

Start the local server:

```bash
php artisan serve
```

---

## Development Notes

- The application uses Laravel 12's `bootstrap/app.php` configuration structure.
- Authentication is handled through Laravel's web/session authentication.
- The booking process uses regular Blade views and Laravel controllers rather than a separate frontend SPA.
- Payment is simulated and is not connected to a real payment provider.
- Contact submissions are validated but are not sent through an email service.
- The application is designed as a local/demo domestic flight booking system and should not be considered production-ready payment or airline infrastructure without additional security, payment, deployment, and operational work.

---

## Project Status

The current project includes the main implemented flow:

```text
Home
→ Flight Search
→ Flight Results
→ Seat Selection
→ Passenger Details
→ Checkout
→ Booking Confirmation
→ Ticket
```

along with:

```text
English / Arabic UI
Customer Profile
Admin Dashboard
Admin Flight Management
Admin Booking Management
```
