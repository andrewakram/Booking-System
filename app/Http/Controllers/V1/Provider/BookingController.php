<?php

namespace App\Http\Controllers\V1\Provider;

use App\Http\Requests\V1\Booking\UpdateStatusRequest;
use App\Http\Resources\V1\Booking\BookingResource;
use App\Interfaces\BookingStatusRepositoryInterface;
use App\Models\Booking;
use App\Services\BookingStatusService;
use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    use ApiResponserTrait;

    private BookingStatusRepositoryInterface $bookingStatusRepository;
    private BookingStatusService $bookingStatusService;

    public function __construct(
        BookingStatusRepositoryInterface $bookingStatusRepository,
        BookingStatusService $bookingStatusService
    ) {
        $this->bookingStatusRepository = $bookingStatusRepository;
        $this->bookingStatusService = $bookingStatusService;
    }

    public function updateStatus(Booking $booking,UpdateStatusRequest $request)
    {
        return $this->successResponse(new BookingResource($this->bookingStatusService->updateStatus($booking,$request)));
    }

}
