<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
     // Show Registration Form
     public function showRegisterForm()
     {
         return view('auth.register');
     }

     // Handle Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'=>'unique:users,email|email',
            'password'=>'required|min:4',
            'phone' => 'required|unique:users,phone',
            'pin' => 'required|min:4|max:4|confirmed',
            'role' => 'required|in:customer,driver',
            'county' => 'required',
            'sub_county' => 'required',

        ]);
        // Debugging step: Check received data
       // dd($request->all());
    
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
            'phone' => $request->phone,
            'pin' => $request->pin,
            'role' => $request->role,
            'county' => $request->county,
            'sub_county' => $request->sub_county,

        ]);

        Auth::login($user); // Auto-login after registration

            // Redirect based on user role
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard')->with('success', 'Welcome, Customer!');
            } elseif ($user->role === 'driver') {
                return redirect()->route('driver.dashboard')->with('success', 'Welcome, Driver!');
            } else {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
            }
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'pin' => 'required|min:4|max:4',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();

        // Check if user exists and verify PIN
        if (!$user || !Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['error' => 'Invalid phone number or PIN']);
        }
        
        
        // Authenticate user
        Auth::login($user);

          // Redirect based on user role
        if ($user->role === 'customer') {
            return redirect()->route('customer.dashboard')->with('success', 'Welcome, Customer!');
        } elseif ($user->role === 'driver') {
            return redirect()->route('driver.dashboard')->with('success', 'Welcome, Driver!');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
        }
        
    }
    //app api login
    public function applogin(LoginRequest $request){
        $request->validated();

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();
         // Check if user exists and verify PIN
         if (!$user || !Hash::check($request->pin, $user->pin)) {
            return response([ 'message'=>'invalid credentials'], 422);
        }

    }
    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
   
}

