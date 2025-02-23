@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h2 class="text-2xl font-bold">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Customers</h3>
            <p class="text-3xl font-bold">{{ $customers }}</p>
            <a href="{{ route('admin.customers') }}" class="text-blue-500 hover:underline">View Customers</a>
        </div>
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Drivers</h3>
            <p class="text-3xl font-bold">{{ $drivers }}</p>
            <a href="{{ route('admin.drivers') }}" class="text-blue-500 hover:underline">View Drivers</a>
        </div>
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold">Ride Requests</h3>
            <p class="text-3xl font-bold">{{ $rides }}</p>
            <a href="{{ route('admin.rides') }}" class="text-blue-500 hover:underline">View Rides</a>
        </div>
    </div>
</div>
@endsection
