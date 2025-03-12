<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    return view('welcome');
});

// Show the login form (Web)
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Handle login submission (Web)
Route::post('/login', function (Request $request) {
    $request->validate([
        'phone' => 'required|exists:users,phone',
        'pin' => 'required|digits:4',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->pin, $user->pin)) {
        throw ValidationException::withMessages([
            'phone' => ['The provided credentials are incorrect.'],
        ]);
    }

    Auth::login($user);

    return redirect()->route('dashboard');
})->name('login');

// Protected Routes (using session-based auth middleware)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $customers = User::where('role', 'customer')->count();
        $drivers = User::where('role', 'driver')->count();
        $completedRides = Booking::where('status', 'completed')->count();

        return view('dashboard', compact('customers', 'drivers', 'completedRides'));
    })->name('dashboard');

    // Customers
    Route::get('/customers', function (Request $request) {
        $query = User::where('role', 'customer')->orderBy('created_at', 'desc');
        
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $customers = $query->get();
        return view('customers.index', compact('customers'));
    })->name('customers.index');

    // Drivers
    Route::get('/drivers', function (Request $request) {
        $query = User::where('role', 'driver')->orderBy('created_at', 'desc');
        
        if ($request->has('county')) {
            $query->where('county', $request->county);
        }
        if ($request->has('sub_county')) {
            $query->where('sub_county', $request->sub_county);
        }

        $drivers = $query->get();
        $counties = config('counties'); // Fetch counties from config

        return view('drivers.index', compact('drivers', 'counties'));
    })->name('drivers.index');

    // Rides
    Route::get('/rides', function () {
        $rides = Booking::orderBy('created_at', 'desc')->get();
        return view('rides.index', compact('rides'));
    })->name('rides.index');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});