<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yurban Ride Portal</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Ride Portal</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('customers.index') }}">Customers</a>
                <a class="nav-link" href="{{ route('drivers.index') }}">Drivers</a>
                <a class="nav-link" href="{{ route('rides.index') }}">Rides</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>