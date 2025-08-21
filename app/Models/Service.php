<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'description',
        'category_id',
        'duration',
        'price',
        'is_published'
    ];

    public function provider() {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function locations() {
        return $this->hasMany(LocationService::class,'service_id');
    }

    /**
     * Scope: only published services
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
