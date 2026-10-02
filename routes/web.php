<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Language switcher — stores the choice in session; SetLocale middleware
// (registered globally, see README) applies it on every subsequent request.
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Flight search results — full booking/checkout flow arrives in the next step.
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');

// Browse every upcoming, bookable flight — the "Show All Scheduled Flights"
// fallback shown when a specific search comes back empty.
Route::get('/flights', [FlightController::class, 'all'])->name('flights.all');

/*
|--------------------------------------------------------------------------
| Guest-only routes (login / register)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes (any logged-in user)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');

        Route::get('/seats', [BookingController::class, 'selectSeats'])->name('seats');
        Route::post('/seats', [BookingController::class, 'storeSeats'])->name('seats.store');

        Route::get('/passengers', [BookingController::class, 'passengerForm'])->name('passengers');
        Route::post('/passengers', [BookingController::class, 'storePassengers'])->name('passengers.store');

        Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::post('/confirm', [BookingController::class, 'confirm'])->name('confirm');

        Route::get('/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('confirmation');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
    });
});

/*
|--------------------------------------------------------------------------
| Admin-only routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('flights', \App\Http\Controllers\Admin\FlightController::class)
        ->except(['show'])
        ->parameters(['flights' => 'flight']);

    Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
});

// صفحة عن الشركة
Route::view('/about', 'about')->name('about');

// صفحة الاتصال بنا
Route::view('/contact', 'contact')->name('contact');




Route::view('/contact', 'contact')->name('contact');

Route::post('/contact', function (Request $request) {

    $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'subject' => ['required', 'string', 'max:150'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    return redirect()
        ->route('contact')
        ->with('success', 'Your message has been sent successfully.');
})->name('contact.send');