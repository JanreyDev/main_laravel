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

        // 🔒 Check if user is blocked
        if ($user->status === 'blocked') {
            // If we store blocked_until column, use that
            if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                return back()->with([
                    'error' => 'Your account is blocked.',
                    'unlock_time' => $user->blocked_until->timestamp
                ]);
            }

            // ✅ Auto-unblock if time passed
            if ($user->blocked_until && now()->greaterThanOrEqualTo($user->blocked_until)) {
                $user->status = 'active';
                $user->failed_attempts = 0;
                $user->blocked_until = null;
                $user->save();
            }
        }

        // 🔑 Check password
        if (!Hash::check($request->password, $user->password)) {
            $user->failed_attempts += 1;

            if ($user->failed_attempts >= 3) {
                $user->status = 'blocked';
                $user->blocked_until = now()->addMinutes(0.5); // store exact unblock time
            }

            $user->last_failed_at = now();
            $user->save();

            return back()->with('error', 'Invalid credentials');
        }

        // ✅ Successful login
        $user->failed_attempts = 0;
        $user->status = 'active';
        $user->blocked_until = null;
        $user->save();

        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        return redirect()->route('dashboard')->with('token', $token);

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
