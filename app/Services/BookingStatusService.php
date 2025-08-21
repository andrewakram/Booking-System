<?php

namespace App\Services;

use App\Events\BookingStatusChanged;
use App\Http\Requests\V1\Booking\UpdateStatusRequest;
use App\Interfaces\BookingStatusRepositoryInterface;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class BookingStatusService
{
    private BookingStatusRepositoryInterface $bookingStatusRepository;

    public function __construct(BookingStatusRepositoryInterface $bookingStatusRepository)
    {
        $this->bookingStatusRepository = $bookingStatusRepository;
    }

    public function updateStatus(Booking $booking, UpdateStatusRequest $request)
    {
        return $this->bookingStatusRepository->updateStatus($booking, $request->validated());
    }
}
