<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride-Sharing Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-gray-100">

    <!-- Navigation -->
    <nav class="bg-blue-600 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-white text-lg font-bold">Ride-Sharing Portal</a>
            <button id="menuToggle" class="text-white md:hidden">
                ☰
            </button>
            <ul id="menu" class="hidden md:flex space-x-6 text-white">
                <li><a href="{{ url('/dashboard') }}" class="hover:underline">Dashboard</a></li>
                <li><a href="{{ url('/customers') }}" class="hover:underline">Customers</a></li>
                <li><a href="{{ url('/drivers') }}" class="hover:underline">Drivers</a></li>
                <li><a href="{{ url('/rides') }}" class="hover:underline">Rides</a></li>
                <li>
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>

</body>
</html>
