<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 20)->unique(); // e.g. BK6538A1F2

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // deleting a user removes their bookings

            $table->foreignId('flight_id')
                ->constrained('flights')
                ->onDelete('restrict'); // protect flight from deletion while bookings exist

            $table->unsignedInteger('total_passengers');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);

            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();

            $table->index('user_id');
            $table->index('flight_id');
            $table->index(['payment_status', 'booking_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
