<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'service_id',
        'customer_id',
        'provider_id',
        'location_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'price',
    ];

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function provider() {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }


}
