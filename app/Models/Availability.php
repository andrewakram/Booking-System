<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'location_id',
        'day_of_week',
        'start_time',
        'end_time'
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
