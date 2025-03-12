<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->count();
        $drivers = User::where('role', 'driver')->count();
        $completedRides = Booking::where('status', 'completed')->count();

        return view('dashboard', compact('customers', 'drivers', 'completedRides'));
    }
}   