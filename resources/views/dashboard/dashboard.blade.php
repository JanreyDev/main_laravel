<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-indigo-600 p-4 text-white flex justify-between items-center">
        <h1 class="text-xl font-bold">Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                Logout
            </button>
        </form>
    </nav>

    <!-- Content -->
    <div class="p-8">
        <h2 class="text-2xl font-semibold mb-6">Welcome, {{ Auth::user()->name }}</h2>

        <!-- User Info -->
        <div class="bg-white shadow rounded p-6 mb-8 space-y-2">
            <h3 class="text-lg font-bold text-indigo-600 mb-3">Current Session Information</h3>

            @if ($location)
                @if (isset($location->isTestIp) && $location->isTestIp)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3">
                        <p class="text-sm text-yellow-700">
                            <strong>⚠️ Development Mode:</strong> Using test IP for location lookup because your real IP
                            ({{ $location->realIp }}) is a private/local address.
                        </p>
                    </div>
                @endif

                @if (isset($location->isRealPublicIp) && $location->isRealPublicIp)
                    <div class="bg-green-50 border-l-4 border-green-400 p-3 mb-3">
                        <p class="text-sm text-green-700">
                            <strong>✅ Real Public IP:</strong> Using your actual public IP address for accurate
                            location.
                        </p>
                    </div>
                @endif

                <p><strong>Your Local IP:</strong> {{ $location->realIp ?? request()->ip() }}</p>
                @if (isset($location->lookupIp) && $location->lookupIp !== $location->realIp)
                    <p><strong>Public IP Used for Lookup:</strong> {{ $location->lookupIp }}</p>
                @endif
                <p><strong>Country:</strong> {{ $location->countryName }}</p>
                <p><strong>Region:</strong> {{ $location->regionName }}</p>
                <p><strong>City:</strong> {{ $location->cityName }}</p>
                @if (isset($location->zipCode))
                    <p><strong>Zip Code:</strong> {{ $location->zipCode }}</p>
                @endif
                @if (isset($location->timezone))
                    <p><strong>Timezone:</strong> {{ $location->timezone }}</p>
                @endif
            @else
                <p><strong>IP Address:</strong> {{ request()->ip() }}</p>
                <p><strong>Location:</strong> Unknown</p>
            @endif

            <hr class="my-4">

            <h3 class="text-lg font-bold text-indigo-600 mb-3 mt-4">Account Information</h3>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p class="text-gray-700">
                <strong>Departments:</strong>
                {{ Auth::user()->departments->pluck('name')->join(', ') }}
            </p>
            <p><strong>Role:</strong> {{ Auth::user()->role }}</p>

            @if (session('token'))
                <p class="mt-4"><strong>JWT Token:</strong></p>
                <textarea class="w-full h-32 border rounded p-2 text-sm" readonly>{{ session('token') }}</textarea>
            @endif
        </div>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- PGO Card --}}
            @if (Auth::user()->departments->contains('name', 'PGO'))
                <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">PGO</h3>
                    <p class="text-gray-600">Provincial Governor's Office</p>
                    <a href="http://localhost:3000/?token={{ session('token') }}"
                        class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        View Details
                    </a>
                </div>
            @endif

            {{-- PPDO Card --}}
            @if (Auth::user()->departments->contains('name', 'PPDO'))
                <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-xl font-bold text-green-600 mb-2">PPDO</h3>
                    <p class="text-gray-600">Provincial Planning and Development Office</p>
                    <a href="http://localhost:3001/?token={{ session('token') }}"
                        class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        View Details
                    </a>
                </div>
            @endif

            {{-- HRM Card --}}
            @if (Auth::user()->departments->contains('name', 'HRM'))
                <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-xl font-bold text-pink-600 mb-2">HRM</h3>
                    <p class="text-gray-600">Human Resource Management</p>
                    <a href="http://localhost:3002/?token={{ session('token') }}"
                        class="mt-4 inline-block bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">
                        View Details
                    </a>
                </div>
            @endif

        </div>
    </div>
</body>

</html>
