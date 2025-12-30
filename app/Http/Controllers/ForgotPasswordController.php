<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Models\AllowedDomain;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function submitForgotPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    $allowed = AllowedDomain::pluck('domain')->toArray();

                    if (!in_array($domain, $allowed)) {
                        $fail("The {$attribute} must be from an allowed domain.");
                    }
                },
            ],
            'message' => 'nullable|string|max:500',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No account found with this email address.');
        }

        // Check if user has reached daily limit (3 requests per day)
        $today = Carbon::today();
        $requestCount = PasswordResetRequest::where('user_id', $user->id)
            ->whereDate('requested_at', $today)
            ->count();

        if ($requestCount >= 3) {
            return back()->with('error', 'You have reached the maximum number of password reset requests for today. Please try again tomorrow.');
        }

        // Auto-reject old pending requests
        PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // Create new password reset request
        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Your password reset request has been submitted. An administrator will review it shortly.');
    }
}