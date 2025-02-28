<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RideController extends Controller
{
    public function bookRide(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                Log::warning('No authenticated user for bookRide', ['request' => $request->all()]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'No authenticated user'], 401);
            }

            $pickup = $request->input('pickup');
            $dropoff = $request->input('dropoff');
            $rideType = $request->input('ride_type');
            $estimatedFare = $request->input('estimated_fare');

            if (!$pickup || !$dropoff || !$rideType || !$estimatedFare) {
                Log::warning('Missing required fields in bookRide', ['request' => $request->all()]);
                return response()->json(['error' => 'Invalid input', 'message' => 'Missing required fields'], 400);
            }

            if (!isset($pickup['lat']) || !isset($pickup['lng']) || !isset($dropoff['lat']) || !isset($dropoff['lng'])) {
                Log::warning('Invalid coordinates in bookRide', ['request' => $request->all()]);
                return response()->json(['error' => 'Invalid coordinates', 'message' => 'Latitude or longitude missing'], 400);
            }

            $booking = Booking::create([
                'user_id' => $user->id,
                'pickup_lat' => $pickup['lat'],
                'pickup_lng' => $pickup['lng'],
                'dropoff_lat' => $dropoff['lat'],
                'dropoff_lng' => $dropoff['lng'],
                'ride_type' => $rideType,
                'estimated_fare' => $estimatedFare,
                'status' => 'pending',
            ]);

            $booking->update(['status' => 'confirmed', 'driver_id' => 1]); // Simulate driver assignment

            return response()->json([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'driver_id' => $booking->driver_id ?? 'DRIVER123',
                'estimated_arrival' => '5 minutes',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Booking error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }


    public function cancelRide($bookingId)
    {
        try {
            $booking = Booking::where('id', $bookingId)->where('user_id', auth()->id())->firstOrFail();
            $booking->update(['status' => 'cancelled']);
            return response()->json(['message' => 'Ride cancelled successfully']);
        } catch (\Exception $e) {
            Log::error('Cancel ride error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getRideStatus($bookingId)
    {
        try {
            $booking = Booking::where('id', $bookingId)->where('user_id', auth()->id())->firstOrFail();
            return response()->json([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'driver_id' => $booking->driver_id ?? 'DRIVER123',
                'estimated_arrival' => '5 minutes',
            ]);
        } catch (\Exception $e) {
            Log::error('Get ride status error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }
}
