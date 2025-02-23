@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h2 class="text-2xl font-bold">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Customers</h3>
            <p class="text-3xl font-bold">{{ $customers }}</p>
        </div>
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Drivers</h3>
            <p class="text-3xl font-bold">{{ $drivers }}</p>
        </div>
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Completed Rides</h3>
            <p class="text-3xl font-bold">{{ $completed_rides }}</p>
        </div>
    </div>

    <canvas id="dashboardChart" class="mt-6"></canvas>
</div>

<script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Customers', 'Drivers', 'Completed Rides'],
            datasets: [{
                label: 'System Overview',
                data: [{{ $customers }}, {{ $drivers }}, {{ $completed_rides }}],
                backgroundColor: ['blue', 'green', 'orange']
            }]
        }
    });
</script>
@endsection
