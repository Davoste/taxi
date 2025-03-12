<?php 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->orderBy('created_at', 'desc');
        
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $customers = $query->get();
        return view('customers.index', compact('customers'));
    }
}