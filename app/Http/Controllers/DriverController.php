<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
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
    }
}