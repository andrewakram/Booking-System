<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilityOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'location_id',
        'date',
        'start_time',
        'end_time',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
