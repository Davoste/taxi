<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings'; // Explicitly set table name
    protected $fillable = [
        'user_id', 'driver_id', 'pickup_lat', 'pickup_lng', 'dropoff_lat',
        'dropoff_lng', 'ride_type', 'estimated_fare', 'status', 'passengers'
    ];

    protected $casts = [
        'pickup_lat' => 'float',
        'pickup_lng' => 'float',
        'dropoff_lat' => 'float',
        'dropoff_lng' => 'float',
        'estimated_fare' => 'float',
    ];
}