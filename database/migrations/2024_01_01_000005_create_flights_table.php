<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            $table->foreignId('airline_id')
                ->constrained('airlines')
                ->onDelete('cascade'); // deleting an airline removes its flights

            $table->string('flight_number', 20);

            $table->foreignId('departure_airport_id')
                ->constrained('airports')
                ->onDelete('restrict'); // protect airport from deletion while flights reference it

            $table->foreignId('arrival_airport_id')
                ->constrained('airports')
                ->onDelete('restrict');

            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('available_seats');
            $table->enum('status', ['scheduled', 'delayed', 'cancelled', 'completed'])
                ->default('scheduled');

            $table->timestamps();

            $table->index(['departure_airport_id', 'arrival_airport_id']);
            $table->index('departure_time');
            $table->index('status');
            $table->index('flight_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
