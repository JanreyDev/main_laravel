<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Invalid credentials');
        }

        // 🔒 Check if blocked
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
                $user->blocked_until = now()->addMinutes(0.5);
                $user->save();

                return back()->with([
                    'error' => 'Your account is blocked. Please try again in:',
                    'unlock_time' => $user->blocked_until->timestamp
                ]);
            }

            $user->save();

            // ✅ Show remaining attempts
            $remaining = 3 - $user->failed_attempts;
            return back()->with('error', "Invalid credentials. You have {$remaining} attempt(s) left.");
        }

        // ✅ Successful login
        $user->failed_attempts = 0;
        $user->status = 'active';
        $user->blocked_until = null;
        $user->save();

        Auth::login($user);

        // 🔑 Generate JWT token 
        $token = JWTAuth::fromUser($user);

        return redirect()->intended(route('dashboard'))->with('token', $token);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
