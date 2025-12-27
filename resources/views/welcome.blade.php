<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Login
            </button>
        </form>

        @if (session('error'))
            <p class="mt-4 text-red-600 text-sm">{{ session('error') }}</p>
        @endif

        @if (session('unlock_time'))
            <p id="countdown"></p>
            <script>
                const unlockTime = {{ session('unlock_time') }} * 1000; // convert to ms
                const countdownEl = document.getElementById('countdown');

                function updateCountdown() {
                    const timeLeft = unlockTime - Date.now();

                    if (timeLeft <= 0) {
                        countdownEl.textContent = "You can try logging in again now.";
                        return;
                    }

                    const minutes = Math.floor(timeLeft / 1000 / 60);
                    const seconds = Math.floor((timeLeft / 1000) % 60);

                    countdownEl.textContent = `Try again in ${minutes}:${seconds.toString().padStart(2, '0')}`;
                }

                setInterval(updateCountdown, 1000);
                updateCountdown();
            </script>
        @endif
    </div>
</body>

</html>
