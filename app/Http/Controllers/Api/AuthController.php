<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Stevebauman\Location\Facades\Location;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\AllowedDomain;

class AuthController extends Controller
{

    public function index(Request $request)
    {
        // Example: real client IP
        // $userIp = $request->ip();

        // // Or use a sample IP from Tarlac City
        // $userIp = '1.37.82.3';

        // $location = Location::get($userIp);

        // return $location;
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 🔒 Check allowed domain
        $domain = substr(strrchr($request->email, "@"), 1);
        $allowed = \App\Models\AllowedDomain::pluck('domain')->toArray();

        if (!in_array($domain, $allowed)) {
            return back()->with('error', 'Login failed. The email must be from an allowed domain.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Invalid credentials');
        }

        // 🚫 Check if blocked
        if ($user->status === 'blocked') {
            if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                return back()->with([
                    'error' => 'Your account is blocked. Please try again in:',
                    'unlock_time' => $user->blocked_until->timestamp
                ]);
            } else {
                // ✅ Auto-unblock after time passed
                $user->status = 'active';
                $user->failed_attempts = 0;
                $user->blocked_until = null;
                $user->save();
            }
        }

        // 🔑 Check password
        if (!Hash::check($request->password, $user->password)) {
            $user->failed_attempts += 1;
            $user->last_failed_at = now();

            if ($user->failed_attempts >= 3) {
                // 🚫 Block after 3 wrong attempts
                $user->status = 'blocked';
                $user->blocked_until = now()->addMinutes(3);
                $user->save();

                return back()->with([
                    'error' => 'Your account is blocked. Please try again in:',
                    'unlock_time' => $user->blocked_until->timestamp
                ]);
            }

            $user->save();

            $remaining = 3 - $user->failed_attempts;
            return back()->with('error', "Invalid credentials. You have {$remaining} attempt(s) left.");
        }

        // ✅ Successful login
        $user->failed_attempts = 0;
        $user->status = 'active';
        $user->blocked_until = null;
        $user->save();

        Auth::login($user);

        // 🔑 Generate JWT token if you still want it in session
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        return redirect()->intended(route('dashboard'))->with('token', $token);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                    function ($attribute, $value, $fail) {
                        $domain = substr(strrchr($value, "@"), 1);
                        $allowed = AllowedDomain::pluck('domain')->toArray();

                        if (!in_array($domain, $allowed)) {
                            $fail("The {$attribute} must be from an allowed domain.");
                        }
                    },
                ],
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // ✅ Create user (status/role defaults handled by DB migration)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Refresh to include DB defaults like status and role
        $user->refresh();

        // 🔑 Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }
}

