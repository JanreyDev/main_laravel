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
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border">
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

        <!-- Contact Administration Section -->
        <div class="mt-6 text-center border-t border-gray-200 pt-6">
            <p class="text-sm text-gray-600 mb-3">Need help with your account?</p>

            <!-- Gmail Compose Button -->
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=janreyminadev@gmail.com&su=Account%20Assistance%20Request&body=Hello%20Admin,%0D%0A%0D%0AI%20need%20assistance%20with%20my%20account.%0D%0A%0D%0AMy%20email:%20%0D%0AIssue:%20%0D%0A%0D%0AThank%20you."
                target="_blank"
                class="inline-flex items-center justify-center w-full bg-white border-2 border-gray-300 text-gray-700 py-2.5 px-4 rounded-md hover:bg-gray-50 hover:border-indigo-500 transition-all duration-200 font-medium text-sm">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Contact Administration via Email
            </a>

            <!-- Alternative: Show Email Address -->
            <p class="text-xs text-gray-500 mt-3">
                Or email directly:
                <span class="font-medium text-gray-700">janreyminadev@gmail.com</span>
            </p>
        </div>

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
