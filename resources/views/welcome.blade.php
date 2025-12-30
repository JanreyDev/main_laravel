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

            <!-- Forgot Password Link -->
            <div class="text-right">
                <a href="{{ route('forgot-password') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Login
            </button>
        </form>

        @if (session('error'))
            <div class="mt-4 text-red-600 text-sm alert alert-danger">
                {{ session('error') }}
                @if (session('unlock_time'))
                    <span id="timer"></span>
                @endif
            </div>

            @if (session('unlock_time'))
                <script>
                    const unlockTime = {{ session('unlock_time') }} * 1000;
                    const countdownEl = document.getElementById('timer');
                    const loginBtn = document.querySelector('button[type="submit"]');

                    function updateCountdown() {
                        const now = Date.now();
                        let diff = Math.floor((unlockTime - now) / 1000);

                        if (diff <= 0) {
                            countdownEl.innerText = " 0:00";
                            loginBtn.disabled = false;
                            return;
                        }

                        const minutes = Math.floor(diff / 60);
                        const seconds = diff % 60;
                        countdownEl.innerText = ` ${minutes}:${seconds.toString().padStart(2, '0')}`;
                        loginBtn.disabled = true;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                </script>
            @endif
        @endif

        @if (session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>

</html>
