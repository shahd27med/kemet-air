<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Sample passenger names used to fill demo bookings.
     */
    private const SAMPLE_PASSENGERS = [
        ['full_name' => 'Ahmed Mostafa', 'gender' => 'male', 'dob' => '1990-04-12'],
        ['full_name' => 'Nourhan Ahmed', 'gender' => 'female', 'dob' => '1992-08-23'],
        ['full_name' => 'Mona Youssef', 'gender' => 'female', 'dob' => '1988-01-30'],
        ['full_name' => 'Karim Adel', 'gender' => 'male', 'dob' => '1995-11-05'],
        ['full_name' => 'Sara Ibrahim', 'gender' => 'female', 'dob' => '2000-03-17'],
        ['full_name' => 'Omar Hassan', 'gender' => 'male', 'dob' => '1985-06-09'],
        ['full_name' => 'Laila Fathy', 'gender' => 'female', 'dob' => '1998-09-21'],
    ];

    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $flights = Flight::with('seats')->get();

        if ($customers->isEmpty() || $flights->isEmpty()) {
            return;
        }

        // Demo booking 1: single passenger, no discount.
        $this->createBooking($customers[0], $flights[0], 1, 'paid', 'confirmed');

        // Demo booking 2: 3 passengers -> qualifies for the 5% tier.
        $this->createBooking($customers[1], $flights[1], 3, 'paid', 'confirmed');

        // Demo booking 3: 5 passengers -> qualifies for the 10% tier.
        $this->createBooking($customers[2], $flights[2], 5, 'pending', 'pending');

        // Demo booking 4: 2 passengers, cancelled example.
        $this->createBooking($customers[3], $flights[3], 2, 'refunded', 'cancelled');
    }

    private function createBooking(User $user, Flight $flight, int $passengerCount, string $paymentStatus, string $bookingStatus): void
    {
        DB::transaction(function () use ($user, $flight, $passengerCount, $paymentStatus, $bookingStatus) {
            $pricing = Booking::calculatePricing((float) $flight->price, $passengerCount);

            $booking = Booking::create([
                'user_id' => $user->id,
                'flight_id' => $flight->id,
                'total_passengers' => $passengerCount,
                'subtotal' => $pricing['subtotal'],
                'discount_amount' => $pricing['discount_amount'],
                'final_price' => $pricing['final_price'],
                'payment_status' => $paymentStatus,
                'booking_status' => $bookingStatus,
            ]);

            // Assign free seats to passengers for this flight.
            $availableSeats = $flight->seats()
                ->where('is_booked', false)
                ->orderBy('seat_number')
                ->take($passengerCount)
                ->get();

            $samplePassengers = collect(self::SAMPLE_PASSENGERS)->shuffle()->take($passengerCount)->values();

            foreach ($samplePassengers as $index => $passengerData) {
                $seat = $availableSeats->get($index);

                Passenger::create([
                    'booking_id' => $booking->id,
                    'full_name' => $passengerData['full_name'],
                    'national_id_or_passport' => '29' . str_pad((string) random_int(1, 99999999999), 12, '0', STR_PAD_LEFT),
                    'date_of_birth' => $passengerData['dob'],
                    'gender' => $passengerData['gender'],
                    'seat_id' => $seat?->id,
                ]);

                $seat?->update(['is_booked' => true]);
            }

            if ($bookingStatus !== 'cancelled') {
                $flight->decrementAvailableSeats($passengerCount);
            }

            if (in_array($paymentStatus, ['paid', 'refunded'], true)) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'credit_card',
                    'amount' => $booking->final_price,
                    'status' => $paymentStatus === 'paid' ? 'completed' : 'refunded',
                ]);
            }
        });
    }
}
