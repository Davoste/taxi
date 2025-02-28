<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function drivers(Request $request)
    {
        // Get all drivers
        $drivers = User::where('role', 'driver')
                       ->when($request->county, function ($query, $county) {
                           return $query->where('county', $county);
                       })
                       ->when($request->sub_county, function ($query, $subCounty) {
                           return $query->where('sub_county', $subCounty);
                       })
                       ->orderBy('created_at', 'desc')
                       ->get();

       // Load counties from config
       $counties = config('counties', []);
       // Debug to check if counties are loaded
       dd($counties);
       // Get sub-counties for the selected county (if any)
       $selectedCounty = $request->county;
       $sub_counties = $selectedCounty ? ($counties[$selectedCounty] ?? []) : [];

        return view('admin.drivers', compact('drivers', 'counties', 'sub_counties'));
    }
}
