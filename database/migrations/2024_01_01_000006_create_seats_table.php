<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('flight_id')
                ->constrained('flights')
                ->onDelete('cascade'); // deleting a flight removes its seat map

            $table->string('seat_number', 10); // e.g. A1, A2, B3
            $table->enum('seat_class', ['economy', 'business'])->default('economy');
            $table->boolean('is_booked')->default(false);

            $table->timestamps();

            // A seat number must be unique per flight
            $table->unique(['flight_id', 'seat_number']);
            $table->index(['flight_id', 'is_booked']);
            $table->index('seat_class');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
