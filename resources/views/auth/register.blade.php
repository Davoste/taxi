@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-center text-2xl font-bold">Register</h2>

        @if ($errors->any())
            <div class="bg-red-100 p-3 mt-3 text-red-600 rounded">
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            <div class="mt-4">
                <label class="block">Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mt-4">
                <label class="block">Email</label>
                <input type="text" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mt-4">
                <label class="block">Password</label>
                <input type="text" name="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mt-4">
                <label class="block">Phone Number</label>
                <input type="text" name="phone" class="w-full p-2 border rounded" required>
            </div>

            <div class="mt-4">
                <label class="block">4-digit PIN</label>
                <input type="password" name="pin" class="w-full p-2 border rounded" required>
            </div>

            <div class="mt-4">
                <label class="block">Confirm PIN</label>
                <input type="password" name="pin_confirmation" class="w-full p-2 border rounded" required>
            </div>
            <!-- County Dropdown -->
         
            <div class="mt-4">
                <label class="block">County</label>
                <select name="county" id="county" class="w-full p-2 border rounded" required>
                    <option value="">Select County</option>
                    @if (config('counties') === null)
                        <p class="text-red-500">Error: County data is missing in config.</p>
                    @else
                        <p class="text-green-500">Debug: County Data Loaded from Config</p>
                        <pre>{{ json_encode(config('counties')) }}</pre>

                    @endif


                </select>
            </div>

            <!-- Sub-County Dropdown (Dynamically Updated) -->
            <div class="mt-4">
                <label class="block">Sub-County</label>
                <select name="sub_county" id="sub_county" class="w-full p-2 border rounded" required>
                    <option value="">Select Sub-County</option>
                </select>
            </div>
            <div class="mt-4">
                <label class="block">Role</label>
                <select name="role" class="w-full p-2 border rounded" required>
                    <option value="customer">Customer</option>
                    <option value="driver">Driver</option>
                    <option value="driver">Admin</option>
                </select>
            </div>

            <button type="submit" class="w-full mt-4 bg-blue-500 text-white p-2 rounded">Register</button>
        </form>

        <p class="text-center mt-4">Already have an account? <a href="{{ url('/login') }}" class="text-blue-500">Login</a></p>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let counties = @json(config('counties'));

        console.log("Loaded Counties Data:", counties); // Debugging Step

        if (!counties || Object.keys(counties).length === 0) {
            console.error("Error: County data is missing.");
            return;
        }

        const countySelect = document.getElementById('county');
        const subCountySelect = document.getElementById('sub_county');

        // Populate County Dropdown
        for (let county in counties) {
            let option = document.createElement('option');
            option.value = county;
            option.textContent = county;
            countySelect.appendChild(option);
        }

        // Update Sub-County Dropdown when County is Selected
        countySelect.addEventListener('change', function() {
            const selectedCounty = this.value;
            subCountySelect.innerHTML = '<option value="">Select Sub-County</option>';

            console.log("Selected County:", selectedCounty);
            console.log("Available Sub-Counties:", counties[selectedCounty]);

            if (counties[selectedCounty]) {
                counties[selectedCounty].forEach(subCounty => {
                    let option = document.createElement('option');
                    option.value = subCounty;
                    option.textContent = subCounty;
                    subCountySelect.appendChild(option);
                });
            }
        });
    });
</script>

@endsection
