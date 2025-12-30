<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Mail\PasswordResetApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminPasswordResetController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        // Get admin's departments
        $adminDepartments = $admin->departments->pluck('id');

        // Debug: Check if admin has departments
        if ($adminDepartments->isEmpty()) {
            return view('admin.password-reset-requests', [
                'requests' => collect([]),
                'debug' => [
                    'message' => 'Admin has no departments assigned',
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                ]
            ]);
        }

        // Get all password reset requests
        $allRequests = PasswordResetRequest::with('user.departments')->get();

        // Get password reset requests from users in the same departments
        $requests = PasswordResetRequest::with('user.departments')
            ->whereHas('user.departments', function ($query) use ($adminDepartments) {
                $query->whereIn('departments.id', $adminDepartments);
            })
            ->orderBy('requested_at', 'desc')
            ->paginate(15);

        // Debug information
        $debug = [
            'admin_departments' => $adminDepartments->toArray(),
            'total_requests' => PasswordResetRequest::count(),
            'filtered_requests' => $requests->total(),
            'all_requests_preview' => $allRequests->map(function ($req) {
                return [
                    'id' => $req->id,
                    'email' => $req->email,
                    'user_departments' => $req->user->departments->pluck('id')->toArray(),
                ];
            })->toArray(),
        ];

        return view('admin.password-reset-requests', compact('requests', 'debug'));
    }

    public function setPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $resetRequest = PasswordResetRequest::findOrFail($id);

        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Get the user
        $user = User::findOrFail($resetRequest->user_id);

        // Update user password and reset security fields
        $user->password = Hash::make($request->new_password);
        $user->failed_attempts = 0;
        $user->status = 'active';
        $user->blocked_until = null;
        $user->save();

        // Update request status
        $admin = auth()->user();
        $resetRequest->status = 'approved';
        $resetRequest->handled_by = $admin->id;
        $resetRequest->handled_by_email = $admin->email;
        $resetRequest->handled_by_name = $admin->name;
        $resetRequest->handled_at = now();
        $resetRequest->save();

        // Send email to user
        try {
            Mail::to($user->email)->send(new PasswordResetApproved($user, $request->new_password));
        } catch (\Exception $e) {
            // Log error but don't fail the process
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
        }

        return back()->with('success', 'Password has been reset successfully and email sent to the user.');
    }

    public function reject($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Update request status
        $admin = auth()->user();
        $resetRequest->status = 'rejected';
        $resetRequest->handled_by = $admin->id;
        $resetRequest->handled_by_email = $admin->email;
        $resetRequest->handled_by_name = $admin->name;
        $resetRequest->handled_at = now();
        $resetRequest->save();

        return back()->with('success', 'Password reset request has been rejected.');
    }
}