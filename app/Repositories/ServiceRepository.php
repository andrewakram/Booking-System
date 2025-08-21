<?php

namespace App\Repositories;

use App\Interfaces\ServiceRepositoryInterface;
use App\Models\LocationService;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServiceRepository implements ServiceRepositoryInterface {

    public function showPublishedServices(){
        return Service::published()->with(['provider','category','locations'])->get();
    }

    public function addMultiService(array $data)
    {
        \DB::beginTransaction();

        foreach ($data['data'] as $item){
            $service = Service::create([
                'provider_id'   => auth()->id(),
                'name'          => $item['name'],
                'description'   => $item['description'],
                'duration'      => $item['duration'],
                'price'         => $item['price'],
                'category_id'   => $item['category_id'],
                'is_published'  => isset($item['status']) ? $item['status'] : true,
            ]);

            $this->addServiceToLocation($service->id,$item['location_ids']);
        }

        \DB::commit();

        return true;
    }

    private function addServiceToLocation($serviceId,$locationIds)
    {
        foreach ($locationIds as $locationId){
            LocationService::create([
                'service_id'    => $serviceId,
                'location_id'   => $locationId,
            ]);
        }
    }


}
