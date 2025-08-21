<?php

namespace App\Services;

use App\Http\Requests\V1\Locations\CreateMultiLocationRequest;
use App\Interfaces\LocationRepositoryInterface;

class LocationService{

    private LocationRepositoryInterface $locatioRepository;

    public function __construct(LocationRepositoryInterface $locatioRepository)
    {
        $this->locatioRepository = $locatioRepository;
    }

    public function addMultiLocation(CreateMultiLocationRequest $request)
    {
        return $this->locatioRepository->addMultiLocation($request->validated());
    }

}
