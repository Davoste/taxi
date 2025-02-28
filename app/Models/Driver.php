<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'vehicle_type', 'license_plate', 'is_available'];
}