<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller

{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }
    // handle login
    public function login(Request $request)
    {
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
    }
}