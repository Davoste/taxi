<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Api\DriverController;
//
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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

Route::post('/applogin', function (Request $request) {
    try {
        // Validate request
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'pin' => 'required|min:4|max:4',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();

        // Check if user exists and verify PIN
        if (!$user || !Hash::check($request->pin, $user->pin)) {
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