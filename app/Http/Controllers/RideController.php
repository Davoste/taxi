<?php 
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Models\Booking;

class RideController extends Controller
{
    public function index()
    {
        $rides = Booking::orderBy('created_at', 'desc')->get();
        return view('rides.index', compact('rides'));
    }
}