<?php

namespace App\Services;

use App\Http\Requests\V1\Services\CreateMultiServiceRequest;
use App\Interfaces\ServiceRepositoryInterface;

class ServiceService{

    private ServiceRepositoryInterface $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function showPublishedServices()
    {
        return $this->serviceRepository->showPublishedServices();
    }

    public function addMultiService(CreateMultiServiceRequest $request)
    {
        return $this->serviceRepository->addMultiService($request->validated());
    }

}
