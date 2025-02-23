    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto mt-10">
        <h2 class="text-2xl font-bold">Registered Drivers</h2>

        <form method="GET" class="mt-4 flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
        <select name="county" class="p-2 border rounded w-full md:w-auto">
            <option value="">Select County</option>
            @if (empty($counties))
                <p class="text-red-500">Error: County data is missing.</p>
            @else
                <p class="text-green-500">County data loaded successfully.</p>
                <pre>{{ json_encode($counties) }}</pre>
            @endif

        </select>


            <select name="sub_county" class="p-2 border rounded w-full md:w-auto">
                <option value="">Select Sub-County</option>
                @if (empty($sub_counties))
                    <p class="text-red-500">Error: Sub-county data is missing.</p>
                @else
                    <p class="text-green-500">Sub-county data loaded successfully.</p>
                    <pre>{{ json_encode($sub_counties) }}</pre>
                @endif

            </select>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full md:w-auto">Filter</button>
        </form>

        <div class="overflow-x-auto mt-6">
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">ID</th>
                        <th class="p-2">Phone</th>
                        <th class="p-2">County</th>
                        <th class="p-2">Sub-County</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                        <tr class="border-b">
                            <td class="p-2">{{ $driver->id }}</td>
                            <td class="p-2">{{ $driver->phone }}</td>
                            <td class="p-2">{{ $driver->county }}</td>
                            <td class="p-2">{{ $driver->sub_county }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection
