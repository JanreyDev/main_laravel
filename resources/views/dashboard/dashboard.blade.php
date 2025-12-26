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
            <p>Login Location: {{ session('location') }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Department:</strong> {{ Auth::user()->department }} ({{ Auth::user()->department_code }})</p>
            <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
            @if (session('token'))
                <p class="mt-4"><strong>JWT Token:</strong></p>
                <textarea class="w-full h-32 border rounded p-2 text-sm" readonly>{{ session('token') }}</textarea>
            @endif
        </div>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- PGO Card --}}
            @if (Auth::user()->department === 'PGO' || Auth::user()->department === 'Special')
                <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">PGO</h3>
                    <p class="text-gray-600">Provincial Governor’s Office</p>
                    <a href="http://localhost:3000/?token={{ session('token') }}"
                        class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        View Details
                    </a>
                </div>
            @endif

            {{-- PPDO Card --}}
            @if (Auth::user()->department === 'PPDO' || Auth::user()->department === 'Special')
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
            @if (Auth::user()->department === 'HRM' || Auth::user()->department === 'Special')
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
