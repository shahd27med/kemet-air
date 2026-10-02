<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FlightSeeder extends Seeder
{
    /**
     * Each seat "row" has 4 seats: A, B, C, D.
     * The first 2 rows of every flight are business class,
     * the remaining rows are economy.
     */
    private const SEAT_LETTERS = ['A', 'B', 'C', 'D'];
    private const BUSINESS_ROWS = 2;
    private const ECONOMY_ROWS = 13;

    public function run(): void
    {
        $airlines = Airline::all()->keyBy('code');
        $airports = Airport::all()->keyBy('code');

        $totalSeats = (self::BUSINESS_ROWS + self::ECONOMY_ROWS) * count(self::SEAT_LETTERS); // 60

        // [airline_code, flight_number, from_code, to_code, days_from_now, departure_time, duration_minutes, price]
        $flightPlan = [
            ['MS', 'MS0101', 'CAI', 'HRG', 1, '07:00', 60, 1850.00],
            ['MS', 'MS0102', 'HRG', 'CAI', 1, '09:15', 60, 1850.00],
            ['SM', 'SM0201', 'CAI', 'SSH', 1, '08:30', 55, 1950.00],
            ['SM', 'SM0202', 'SSH', 'CAI', 1, '10:45', 55, 1950.00],
            ['MS', 'MS0301', 'CAI', 'ASW', 2, '06:45', 90, 2400.00],
            ['MS', 'MS0302', 'ASW', 'CAI', 2, '09:00', 90, 2400.00],
            ['NP', 'NP0401', 'CAI', 'LXR', 2, '11:20', 80, 2200.00],
            ['NP', 'NP0402', 'LXR', 'CAI', 2, '13:20', 80, 2200.00],
            ['MS', 'MS0501', 'CAI', 'ALX', 3, '14:00', 45, 1400.00],
            ['MS', 'MS0502', 'ALX', 'CAI', 3, '16:00', 45, 1400.00],
            ['SM', 'SM0601', 'ALX', 'HRG', 4, '12:10', 75, 2350.00],
            ['NP', 'NP0602', 'HRG', 'SSH', 5, '17:30', 40, 1300.00],
        ];

        foreach ($flightPlan as [$airlineCode, $flightNumber, $fromCode, $toCode, $daysFromNow, $time, $durationMinutes, $price]) {
            $airline = $airlines->get($airlineCode);
            $departureAirport = $airports->get($fromCode);
            $arrivalAirport = $airports->get($toCode);

            if (! $airline || ! $departureAirport || ! $arrivalAirport) {
                continue;
            }

            $departureTime = Carbon::parse(Carbon::now()->addDays($daysFromNow)->format('Y-m-d') . ' ' . $time);
            $arrivalTime = (clone $departureTime)->addMinutes($durationMinutes);

            $flight = Flight::updateOrCreate(
                [
                    'flight_number' => $flightNumber,
                    'departure_time' => $departureTime,
                ],
                [
                    'airline_id' => $airline->id,
                    'departure_airport_id' => $departureAirport->id,
                    'arrival_airport_id' => $arrivalAirport->id,
                    'arrival_time' => $arrivalTime,
                    'price' => $price,
                    'total_seats' => $totalSeats,
                    'available_seats' => $totalSeats,
                    'status' => 'scheduled',
                ]
            );

            $this->generateSeatsForFlight($flight);
        }
    }

    private function generateSeatsForFlight(Flight $flight): void
    {
        // Skip if this flight already has a seat map.
        if ($flight->seats()->exists()) {
            return;
        }

        $seatsToInsert = [];
        $row = 1;

        for ($i = 0; $i < self::BUSINESS_ROWS; $i++) {
            foreach (self::SEAT_LETTERS as $letter) {
                $seatsToInsert[] = [
                    'flight_id' => $flight->id,
                    'seat_number' => $letter . $row,
                    'seat_class' => 'business',
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $row++;
        }

        for ($i = 0; $i < self::ECONOMY_ROWS; $i++) {
            foreach (self::SEAT_LETTERS as $letter) {
                $seatsToInsert[] = [
                    'flight_id' => $flight->id,
                    'seat_number' => $letter . $row,
                    'seat_class' => 'economy',
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $row++;
        }

        Seat::insert($seatsToInsert);
    }
}
