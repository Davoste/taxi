@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-center text-2xl font-bold">Login</h2>

        @if ($errors->any())
            <div class="bg-red-100 p-3 mt-3 text-red-600 rounded">
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mt-4">
                <label class="block">Phone Number</label>
                <input type="text" name="phone" class="w-full p-2 border rounded" required>
            </div>
            <div class="mt-4">
                <label class="block">4-digit PIN</label>
                <input type="password" name="pin" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full mt-4 bg-blue-500 text-white p-2 rounded">Login</button>
            <div class="mt-4">
                <label class="block">Dont have an account. <a href="{{ ('/register') }}">Register Here</a></label>
            </div>
        </form>
    </div>
</div>
@endsection
