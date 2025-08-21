<?php

namespace App\Interfaces;

use App\Models\Booking;

interface BookingStatusRepositoryInterface{

    public function updateStatus(Booking $booking, array $data);

}
