<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->count();
        $drivers = User::where('role', 'driver')->count();
        $completed_rides = Ride::where('status', 'completed')->count();

        return view('dashboard', compact('customers', 'drivers', 'completed_rides'));
    }
}

