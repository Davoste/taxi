@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h2 class="text-2xl font-bold">Registered Customers</h2>

    <form method="GET" class="mt-4 flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
        <input type="date" name="start_date" class="p-2 border rounded w-full md:w-auto">
        <input type="date" name="end_date" class="p-2 border rounded w-full md:w-auto">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full md:w-auto">Filter</button>
    </form>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">ID</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Date Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr class="border-b">
                        <td class="p-2">{{ $customer->id }}</td>
                        <td class="p-2">{{ $customer->phone }}</td>
                        <td class="p-2">{{ $customer->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
