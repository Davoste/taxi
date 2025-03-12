@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Registered Drivers</div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="county" id="county" class="form-select" onchange="updateSubCounties()">
                            <option value="">All Counties</option>
                            @foreach($counties as $county => $subCounties)
                                <option value="{{ $county }}" {{ request('county') == $county ? 'selected' : '' }}>
                                    {{ $county }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="sub_county" id="sub_county" class="form-select">
                            <option value="">All Sub-Counties</option>
                            <!-- Populated dynamically via JavaScript -->
                        </select>
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
                        <th>County</th>
                        <th>Sub-County</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                    <tr>
                        <td>{{ $driver->phone }}</td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->county }}</td>
                        <td>{{ $driver->sub_county }}</td>
                        <td>{{ $driver->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const counties = @json($counties);
       
        const subCountySelect = document.getElementById('sub_county');

        function updateSubCounties() {
            const county = document.getElementById('county').value;
            subCountySelect.innerHTML = '<option value="">All Sub-Counties</option>';
            
            if (county && counties[county]) {
                counties[county].forEach(subCounty => {
                    const option = document.createElement('option');
                    option.value = subCounty;
                    option.text = subCounty;
                    if ("{{ request('sub_county') }}" === subCounty) {
                        option.selected = true;
                    }
                    subCountySelect.appendChild(option);
                });
            }
        }

        // Initial call to populate sub-counties if a county is already selected
        updateSubCounties();
    </script>
@endsection