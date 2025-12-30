<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Forgot Password</h2>

        <p class="text-gray-600 text-sm mb-6 text-center">
            Submit a password reset request. An administrator will review and approve your request.
        </p>

        <form method="POST" action="{{ route('forgot-password.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address <span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Message (Optional)</label>
                <textarea name="message" rows="4" maxlength="500"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border">{{ old('message') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">You can provide additional information about your request.</p>
                @error('message')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Request Password Reset
            </button>
        </form>

        @if (session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to Login
            </a>
        </div>
    </div>
</body>

</html>
