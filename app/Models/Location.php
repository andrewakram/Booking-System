<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'time_zone',
        'lat',
        'lng',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
