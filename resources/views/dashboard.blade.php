@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Dashboard</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Registered Customers</h5>
                    <p class="fs-4">{{ $customers }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Registered Drivers</h5>
                    <p class="fs-4">{{ $drivers }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Completed Rides</h5>
                    <p class="fs-4">{{ $completedRides }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection