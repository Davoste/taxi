@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h2 class="text-2xl font-bold">Ride Requests</h2>
    <table class="w-full border mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">ID</th>
                <th class="p-2">Customer</th>
                <th class="p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rides as $ride)
                <tr class="border-b">
                    <td class="p-2">{{ $ride->id }}</td>
                    <td class="p-2">{{ $ride->customer->phone }}</td>
                    <td class="p-2">{{ ucfirst($ride->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
