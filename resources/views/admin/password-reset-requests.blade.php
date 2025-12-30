<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Password Reset Requests - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-indigo-600 p-4 text-white flex justify-between items-center">
        <h1 class="text-xl font-bold">Admin - Password Reset Requests</h1>
        <div class="flex gap-4 items-center">
            <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <div class="p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold">Password Reset Requests</h2>
            <p class="text-gray-600">Review and manage password reset requests from your department</p>
        </div>

        <!-- Debug Information -->
        @if (isset($debug))
            <div class="mb-4 bg-blue-50 border border-blue-200 p-4 rounded">
                <h3 class="font-bold text-blue-900 mb-2">🔍 Debug Information:</h3>
                <div class="text-sm text-blue-800 space-y-1">
                    @if (isset($debug['message']))
                        <p class="text-red-600 font-bold">{{ $debug['message'] }}</p>
                        <p>Admin ID: {{ $debug['admin_id'] }}</p>
                        <p>Admin Name: {{ $debug['admin_name'] }}</p>
                    @else
                        <p><strong>Your Departments:</strong> {{ implode(', ', $debug['admin_departments']) }}</p>
                        <p><strong>Total Requests in DB:</strong> {{ $debug['total_requests'] }}</p>
                        <p><strong>Requests You Can See:</strong> {{ $debug['filtered_requests'] }}</p>
                        @if (!empty($debug['all_requests_preview']))
                            <details class="mt-2">
                                <summary class="cursor-pointer font-semibold">View All Requests Details</summary>
                                <pre class="mt-2 text-xs bg-white p-2 rounded overflow-auto">{{ json_encode($debug['all_requests_preview'], JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Requests Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($requests as $resetRequest)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $resetRequest->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $resetRequest->email }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Dept: {{ $resetRequest->user->departments->pluck('name')->join(', ') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">IP:</span> {{ $resetRequest->ip_address }}
                                </div>
                                @if ($resetRequest->message)
                                    <div class="text-sm text-gray-600 mt-1 max-w-md">
                                        <span class="font-medium">Message:</span>
                                        <span class="italic">{{ Str::limit($resetRequest->message, 80) }}</span>
                                    </div>
                                @endif
                                @if ($resetRequest->status !== 'pending' && $resetRequest->handled_at)
                                    <div class="text-xs text-gray-500 mt-2 border-t pt-2">
                                        <div><span class="font-medium">Handled by:</span>
                                            {{ $resetRequest->handled_by_name ?? 'N/A' }}</div>
                                        <div><span class="font-medium">Email:</span>
                                            {{ $resetRequest->handled_by_email ?? 'N/A' }}</div>
                                        <div><span class="font-medium">At:</span>
                                            {{ $resetRequest->handled_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($resetRequest->status === 'pending')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif ($resetRequest->status === 'approved')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $resetRequest->requested_at->diffForHumans() }}
                                <div class="text-xs text-gray-400">
                                    {{ $resetRequest->requested_at->format('M d, Y h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($resetRequest->status === 'pending')
                                    <button onclick="openSetPasswordModal({{ $resetRequest->id }})"
                                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 mr-2">
                                        Set Password
                                    </button>
                                    <form method="POST"
                                        action="{{ route('admin.password-reset.reject', $resetRequest->id) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to reject this request?')"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-lg font-semibold mb-2">No password reset requests found.</div>
                                <p class="text-sm">There are no pending requests from users in your department(s).</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- Set Password Modal -->
    <div id="setPasswordModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Set New Password</h3>
                <form id="setPasswordForm" method="POST" action="">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" required minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="new_password_confirmation" required minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeSetPasswordModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Set Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSetPasswordModal(requestId) {
            const modal = document.getElementById('setPasswordModal');
            const form = document.getElementById('setPasswordForm');
            form.action = `/admin/password-reset/${requestId}/set-password`;
            modal.classList.remove('hidden');
        }

        function closeSetPasswordModal() {
            const modal = document.getElementById('setPasswordModal');
            const form = document.getElementById('setPasswordForm');
            form.reset();
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('setPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSetPasswordModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSetPasswordModal();
            }
        });
    </script>
</body>

</html>
