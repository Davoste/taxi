<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DriverController extends Controller
{
    public function index()
    {
        // Get all users where role = 'driver'
        $drivers = User::where('role', 'driver')->get();

        return response()->json([
            'success' => true,
            'drivers' => $drivers
        ], 200);
    }
}
