<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $token = session('token');

        $userIp = $request->ip();
        $location = Location::get($userIp);


        // // Use a dummy public IP for testing
        // $dummyIp = '8.8.8.8'; // Google DNS
        // $location = Location::get($dummyIp);

        // Fallback if lookup fails
        if (!$location) {
            $location = (object) [
                'countryName' => 'Unknown',
                'regionName' => 'Unknown',
                'cityName' => 'Unknown',
            ];
        }

        return view('dashboard.dashboard', compact('user', 'token', 'location'));
    }
}
