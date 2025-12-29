<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use App\Services\IpLocationService;

class DashboardController extends Controller
{
    protected $ipService;

    public function __construct(IpLocationService $ipService)
    {
        $this->ipService = $ipService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $token = session('token');

        // 📍 Get user IP address
        $realIp = $request->ip();

        // For testing locally with private IPs, use a public test IP
        // Check if IP is private (starts with 127., 10., 172.16-31., or 192.168.)
        $isPrivateIp = preg_match('/^(127\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.|192\.168\.)/', $realIp);

        if ($isPrivateIp && config('app.env') === 'local') {
            // Use test public IP from Philippines for development
            $userIp = '203.177.4.11';
            $isTestIp = true;
        } else {
            $userIp = $realIp;
            $isTestIp = false;
        }

        // 🌍 Get location from IP
        $location = Location::get($userIp);

        // Fallback if lookup fails
        if (!$location) {
            $location = (object) [
                'countryName' => 'Unknown',
                'regionName' => 'Unknown',
                'cityName' => 'Unknown',
                'ipAddress' => $userIp,
            ];
        }

        // Add debug info
        $location->realIp = $realIp;
        $location->isTestIp = $isTestIp;

        return view('dashboard.dashboard', compact('user', 'token', 'location'));
    }
}