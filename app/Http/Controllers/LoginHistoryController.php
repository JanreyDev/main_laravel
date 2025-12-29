<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    /**
     * Display login history for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        // Get login history for current user, ordered by most recent first
        $loginHistory = LoginHistory::where('user_id', $user->id)
            ->orderBy('logged_in_at', 'desc')
            ->paginate(10);

        return view('dashboard.login-history', compact('loginHistory'));
    }

    /**
     * Get login history as JSON (for API)
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();

        $loginHistory = LoginHistory::where('user_id', $user->id)
            ->orderBy('logged_in_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $loginHistory
        ]);
    }
}