<?php

namespace App\Http\Controllers\V1\Provider;

use App\Http\Requests\V1\Locations\CreateMultiLocationRequest;
use App\Interfaces\LocationRepositoryInterface;
use App\Services\LocationService;
use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    use ApiResponserTrait;

    private LocationRepositoryInterface $locationRepository;
    private LocationService $locationService;

    public function __construct(
        LocationRepositoryInterface $locationRepository,
        LocationService $locationService
    ) {
        $this->locationRepository = $locationRepository;
        $this->locationService = $locationService;
    }

    public function addMultiLocation(CreateMultiLocationRequest $request)
    {
        return $this->successResponse($this->locationService->addMultiLocation($request));
    }
}
