<?php

namespace App\Http\Controllers\V1\Customer;

use App\Http\Requests\V1\Booking\CreateBookingRequest;
use App\Http\Requests\V1\Booking\GetAvailabilityRequest;
use App\Http\Resources\V1\Booking\BookingResource;
use App\Interfaces\BookingRepositoryInterface;
use App\Services\BookingService;
use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    use ApiResponserTrait;

    private BookingRepositoryInterface $bookingRepository;
    private BookingService $bookingService;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        BookingService $bookingService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingService = $bookingService;
    }

    public function getAvailability(GetAvailabilityRequest $request)
    {
        return $this->successResponse($this->bookingService->getAvailability($request));
    }

    public function book(CreateBookingRequest $request)
    {
        return $this->successResponse(new BookingResource($this->bookingService->book($request)));
    }
}
