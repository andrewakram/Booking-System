<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationService extends Model
{
    protected $fillable = [
        'location_id',
        'service_id',
    ];

    protected $appends = [
        'location_name',
        'location_time_zone',
        'location_lat',
        'location_lng',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function getLocationNameAttribute()
    {
        return $this->location()->first()->name;
    }
    public function getLocationTimeZoneAttribute()
    {
        return $this->location()->first()->time_zone;
    }
    public function getLocationLatAttribute()
    {
        return $this->location()->first()->lat;
    }
    public function getLocationLngAttribute()
    {
        return $this->location()->first()->lng;
    }
}
