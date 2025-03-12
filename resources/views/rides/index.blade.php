@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Ride Requests</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rides as $ride)
                    <tr>
                        <td>{{ $ride->id }}</td>
                        <td>{{ $ride->user->phone }}</td>
                        <td>{{ $ride->driver ? $ride->driver->phone : 'N/A' }}</td>
                        <td>{{ $ride->status }}</td>
                        <td>{{ $ride->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection