<?php

namespace App\Services;

use App\Http\Requests\V1\Booking\CreateBookingRequest;
use App\Http\Requests\V1\Booking\GetAvailabilityRequest;
use App\Interfaces\BookingRepositoryInterface;

class BookingService{

    private BookingRepositoryInterface $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }


    public function getAvailability(GetAvailabilityRequest $request)
    {
        return $this->bookingRepository->getAvailability($request->validated());
    }

    public function book(CreateBookingRequest $request)
    {
        return $this->bookingRepository->book($request->validated());
    }

}
