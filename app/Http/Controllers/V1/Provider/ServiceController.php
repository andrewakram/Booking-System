<?php

namespace App\Http\Controllers\V1\Provider;

use App\Http\Requests\V1\Services\CreateMultiServiceRequest;
use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use App\Services\ServiceService;
use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    use ApiResponserTrait;

    private ServiceRepositoryInterface $serviceRepository;
    private ServiceService $serviceService;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        ServiceService $serviceService
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->serviceService = $serviceService;
    }

    public function addMultiService(CreateMultiServiceRequest $request)
    {
        $this->authorize('create', Service::class);

        return $this->successResponse($this->serviceService->addMultiService($request));
    }
}
