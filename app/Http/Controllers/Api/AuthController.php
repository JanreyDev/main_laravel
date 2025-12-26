<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Invalid credentials');
        }

        $user = Auth::user();

        // Generate JWT token for the authenticated user
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // Pass token to dashboard view
        return redirect()->route('dashboard')->with('token', $token);
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
