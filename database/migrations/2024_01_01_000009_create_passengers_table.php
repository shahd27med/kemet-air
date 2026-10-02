<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade'); // deleting a booking removes its passengers

            $table->string('full_name', 150);
            $table->string('national_id_or_passport', 50);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);

            $table->foreignId('seat_id')
                ->nullable()
                ->unique() // a seat can only ever be assigned to one passenger
                ->constrained('seats')
                ->onDelete('set null'); // if the seat row is removed, keep the passenger record

            $table->timestamps();

            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
