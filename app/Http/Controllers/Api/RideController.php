<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;

class RideController extends Controller
{
    public function requestRide(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'pickup_location' => 'required',
            'destination' => 'required'
        ]);

        $ride = Ride::create([
            'customer_id' => $request->customer_id,
            'pickup_location' => $request->pickup_location,
            'destination' => $request->destination,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Ride request created',
            'ride' => $ride
        ], 201);
    }

    public function assignRide(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'driver_id' => 'required|exists:users,id'
        ]);

        $ride = Ride::findOrFail($request->ride_id);
        $ride->driver_id = $request->driver_id;
        $ride->status = 'assigned';
        $ride->save();

        return response()->json([
            'message' => 'Ride assigned successfully',
            'ride' => $ride
        ]);
    }

    public function allRides()
    {
        $rides = Ride::all();
        return response()->json($rides);
    }
}
