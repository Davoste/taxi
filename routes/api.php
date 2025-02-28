<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Api\DriverController;
//
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\RideController;
//

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function(){
    return response([
        'message'=>'API working'
    ] ,200);
});



Route::post('/login', [AuthController::class, 'applogin']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/appdriverlogin', function (Request $request) {
    try {
        // Validate request
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'pin' => 'required|min:4|max:4',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();

        // Check if user exists and role is 'driver'
        if (!$user || $user->role !== 'driver') {
            return response()->json(['message' => 'Not registered as a driver'], 422);
        }

        // Verify PIN
        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // Generate a Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user details and token
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
            'token' => $token
        ]);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }
});
Route::post('/applogin', function (Request $request) {
    try {
        // Validate request
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'pin' => 'required|min:4|max:4',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();

        // Check if user exists and role is 'customer'
        if (!$user || $user->role !== 'customer') {
            return response()->json(['message' => 'Not registered as a customer'], 422);
        }

        // Verify PIN
        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // Generate a Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user details and token
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
            'token' => $token
        ]);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }
});
Route::post('/appregister', function (Request $request) {
    try {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' =>'required|string|min:6',
            'phone' => 'required|unique:users,phone',
            'pin' => 'required|digits:4',
            'role' => 'required|in:customer,driver,admin',
            'county' => 'required|string',
            'sub_county' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'=>$request->password,
            'phone' => $request->phone,
            'pin' => $request->pin,
            'role' => $request->role,
            'county' => $request->county,
            'sub_county' => $request->sub_county,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response
        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'county' => $user->county,
                'sub_county' => $user->sub_county,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
            ],
            'token' => $token
        ], 201);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }
});
Route::get('/getDrivers', [DriverController::class, 'index']);
Route::get('/config/counties', function () {
    return response()->json(config('counties'));
});
// Public routes
Route::post('/search-places', [RideController::class, 'searchPlaces']);

// Authenticated routes (assuming Sanctum or similar for API authentication)

Route::middleware('auth:sanctum')->group(function () {
  
    Route::get('/available-rides', function (Request $request) {
        try {
            $user = $request->user();
            Log::info('User authenticated', ['user' => $user ? $user->toArray() : null]);
            if (!$user) {
                Log::warning('No authenticated user for available-rides', [
                    'request' => $request->all(),
                    'headers' => $request->headers->all(),
                ]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'Authentication required'], 401);
            }
    
            if (!isset($user->role) || $user->role !== 'driver') {
                Log::warning('User is not a driver', [
                    'user_id' => $user->id ?? 'null',
                    'role' => $user->role ?? 'not set',
                ]);
                return response()->json(['error' => 'Forbidden', 'message' => 'Not a driver'], 403);
            }
    
            $rides = Booking::where('status', 'pending')
                ->whereNull('driver_id')
                ->get();
            Log::info('Bookings queried', ['rides_count' => $rides->count()]);
    
            if ($rides->isEmpty()) {
                return response()->json([], 200);
            }
    
            $rideList = $rides->map(function ($ride) {
                return [
                    'booking_id' => $ride->id,
                    'pickup' => [
                        'lat' => $ride->pickup_lat,
                        'lng' => $ride->pickup_lng,
                    ],
                    'dropoff' => [
                        'lat' => $ride->dropoff_lat,
                        'lng' => $ride->dropoff_lng,
                    ],
                    'ride_type' => $ride->ride_type,
                    'estimated_fare' => (float) $ride->estimated_fare,
                ];
            })->toArray();
    
            Log::info('Available rides fetched', ['rides' => $rideList]);
            return response()->json($rideList, 200);
        } catch (\Exception $e) {
            Log::error('Fetch rides error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'headers' => $request->headers->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });
    
    Route::post('/book-ride', function (Request $request) {
        try {
            $user = $request->user();
            Log::info('User authenticated for book-ride', ['user' => $user ? $user->toArray() : null]);
            if (!$user) {
                Log::warning('No authenticated user for bookRide', ['request' => $request->all()]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'No authenticated user'], 401);
            }
    
            $pickup = $request->input('pickup');
            $dropoff = $request->input('dropoff');
            $rideType = $request->input('ride_type');
            $estimatedFare = $request->input('estimated_fare');
            $passengers = $request->input('passengers');
    
            Log::info('Ride request data received', [
                'pickup' => $pickup,
                'dropoff' => $dropoff,
                'ride_type' => $rideType,
                'estimated_fare' => $estimatedFare,
                'passengers' => $passengers,
            ]);
    
            if (!$pickup || !$dropoff || !$rideType || !$estimatedFare || !$passengers || $estimatedFare <= 0) {
                Log::warning('Missing or invalid required fields in bookRide', ['request' => $request->all()]);
                return response()->json(['error' => 'Invalid input', 'message' => 'Missing or invalid required fields'], 400);
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
                'passengers' => $passengers,
            ]);
    
            Log::info('Booking created', ['booking_id' => $booking->id]);
            $driverOnline = true; // Replace with real driver status check
            return response()->json([
                'booking_id' => $booking->id,
                'driver_status' => $driverOnline ? 'online' : 'offline',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Booking error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });

    Route::post('/driver/status', function (Request $request) {
        try {
            $user = $request->user();
            if (!$user || $user->role !== 'driver') {
                Log::warning('Unauthorized status update attempt', ['user_id' => $user?->id]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'Not a driver'], 403);
            }

            $status = $request->input('status');
            if (!in_array($status, ['online', 'offline'])) {
                return response()->json(['error' => 'Invalid status', 'message' => 'Status must be online or offline'], 400);
            }

            $user->update(['status' => $status]);
            return response()->json(['message' => 'Status updated to ' . $status], 200);
        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });
    Route::post('/accept-ride/{bookingId}', function (Request $request, $bookingId) {
        try {
            $user = $request->user();
            if (!$user || $user->role !== 'driver') {
                return response()->json(['error' => 'Unauthorized', 'message' => 'Not a driver'], 403);
            }

            $booking = Booking::findOrFail($bookingId);
            if ($booking->status !== 'pending') {
                return response()->json(['error' => 'Invalid state', 'message' => 'Ride already assigned or completed'], 400);
            }

            $booking->update(['status' => 'confirmed', 'driver_id' => $user->id]);

            return response()->json([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'driver_id' => $booking->driver_id,
                'estimated_arrival' => '5 minutes',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Accept ride error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });
    Route::get('/driver/profile', function (Request $request) {
        try {
            $user = $request->user();
            if (!$user) {
                Log::warning('No authenticated user for driver/profile', [
                    'request' => $request->all(),
                    'headers' => $request->headers->all(),
                ]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'Authentication required'], 401);
            }

            if (!isset($user->role) || $user->role !== 'driver') {
                Log::warning('User is not a driver for driver/profile', [
                    'user_id' => $user->id ?? 'null',
                    'role' => $user->role ?? 'not set',
                ]);
                return response()->json(['error' => 'Forbidden', 'message' => 'Not a driver'], 403);
            }

            return response()->json([
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email ?? 'N/A',
                'vehicle' => $user->vehicle ?? 'Not assigned', // Add 'vehicle' column to users if needed
            ], 200);
        } catch (\Exception $e) {
            Log::error('Driver profile fetch error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'headers' => $request->headers->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });
    Route::get('/driver/rides', function (Request $request) {
        try {
            $user = $request->user();
            if (!$user) {
                Log::warning('No authenticated user for driver/rides', [
                    'request' => $request->all(),
                    'headers' => $request->headers->all(),
                ]);
                return response()->json(['error' => 'Unauthorized', 'message' => 'Authentication required'], 401);
            }

            if (!isset($user->role) || $user->role !== 'driver') {
                Log::warning('User is not a driver for driver/rides', [
                    'user_id' => $user->id ?? 'null',
                    'role' => $user->role ?? 'not set',
                ]);
                return response()->json(['error' => 'Forbidden', 'message' => 'Not a driver'], 403);
            }

            $rides = Booking::where('driver_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->get();

            if ($rides->isEmpty()) {
                return response()->json([], 200);
            }

            $rideList = $rides->map(function ($ride) {
                return [
                    'booking_id' => $ride->id,
                    'ride_type' => $ride->ride_type,
                    'estimated_fare' => $ride->estimated_fare,
                    'created_at' => $ride->created_at->toIso8601String(),
                ];
            })->toArray();

            return response()->json($rideList, 200);
        } catch (\Exception $e) {
            Log::error('Driver rides fetch error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'headers' => $request->headers->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    });
    

    Route::post('/cancel-ride/{bookingId}', [RideController::class, 'cancelRide']);
    Route::get('/ride-status/{bookingId}', [RideController::class, 'getRideStatus']);

    Route::get('/customer/profile', function (Request $request) {
        $user = $request->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        return response()->json([
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email ?? 'N/A',
        ]);
    });

    Route::get('/customer/rides', function (Request $request) {
        $user = $request->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        $rides = Booking::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return response()->json($rides->map(function ($ride) {
            return [
                'booking_id' => $ride->id,
                'ride_type' => $ride->ride_type,
                'estimated_fare' => $ride->estimated_fare,
                'created_at' => $ride->created_at->toIso8601String(),
                'status' => $ride->status,
            ];
        })->toArray());
    });

    Route::post('/customer/response', function (Request $request) {
        $user = $request->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        $bookingId = $request->input('booking_id');
        $response = $request->input('response');
        $booking = Booking::find($bookingId);
        if ($booking && $booking->user_id == $user->id) {
            // Notify driver app (simulated here, use WebSocket or queue in production)
            return response()->json(['message' => "Response $response sent"], 200);
        }
        return response()->json(['error' => 'Invalid booking'], 400);
    });
});

