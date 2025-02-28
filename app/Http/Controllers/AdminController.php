<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

class AdminController extends Controller
{
    public function dashboard()
    {
        $customers = User::where('role', 'customer')->count();
        $drivers = User::where('role', 'driver')->count();
        $rides = Ride::count();

        return view('admin.dashboard', compact('customers', 'drivers', 'rides'));
    }

    public function customers(Request $request)
    {
        $customers = User::where('role', 'customer')
                         ->when($request->start_date, function ($query, $start) {
                             return $query->whereDate('created_at', '>=', $start);
                         })
                         ->when($request->end_date, function ($query, $end) {
                             return $query->whereDate('created_at', '<=', $end);
                         })
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('admin.customers', compact('customers'));
    }

    public function drivers(Request $request)
    {
        $drivers = User::where('role', 'driver')
                       ->when($request->county, function ($query, $county) {
                           return $query->where('county', $county);
                       })
                       ->when($request->sub_county, function ($query, $subCounty) {
                           return $query->where('sub_county', $subCounty);
                       })
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('admin.drivers', compact('drivers'));
    }

    public function rides()
    {
        $rides = Ride::orderBy('created_at', 'desc')->get();
        return view('admin.rides', compact('rides'));
    }
}
