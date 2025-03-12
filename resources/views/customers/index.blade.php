@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Registered Customers</div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Phone</th>
                        <th>Name</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection