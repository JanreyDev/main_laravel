<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index()
    {
        // Fetch all sessions from the database
        $sessions = DB::table('sessions')->get();

        // Return as JSON
        return response()->json($sessions);
    }
}
