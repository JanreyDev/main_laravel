<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\LoginHistory;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        $ip = request()->ip();

        // prefer X-Forwarded-For when present (first address)
        $xff = request()->header('X-Forwarded-For') ?? request()->header('x-forwarded-for');
        if ($xff) {
            $first = trim(explode(',', $xff)[0]);
            if ($first && !self::isPrivateIp($first)) {
                $ip = $first;
            }
        }

        // if IP is private (localhost/LAN), we'll avoid external lookups
        $isPrivate = self::isPrivateIp($ip);

        $position = $isPrivate ? false : Location::get($ip);

        $country = null;
        $city = null;

        // Location::get() may return false on failure — handle that and fallback to ip-api.com
        if (is_object($position)) {
            $country = $position->countryName ?? null;
            $city = $position->cityName ?? null;
        } else {
            try {
                $resp = Http::get("http://ip-api.com/json/{$ip}");
                if ($resp->ok()) {
                    $data = $resp->json();
                    if (isset($data['status']) && $data['status'] === 'success') {
                        $country = $data['country'] ?? null;
                        $city = $data['city'] ?? null;
                    }
                }
            } catch (\Throwable $e) {
                // ignore network errors and leave country/city null
            }
        }

        $locationString = trim((($city ? $city . ', ' : '') . ($country ?? '')), ', ');
        if ($isPrivate) {
            // Friendly label for local/private addresses
            $locationString = 'Localhost';
        } else {
            $locationString = $locationString ?: 'Unknown';
        }

        // store in session payload
        try {
            session(['location' => $locationString]);
        } catch (\Throwable $e) {
            // ignore if session not available
        }

        // If using DB session driver, update sessions table row as well
        try {
            $sessionId = session()->getId();
            if ($sessionId) {
                DB::table('sessions')->where('id', $sessionId)->update(['location' => $locationString]);
            }
        } catch (\Throwable $e) {
            // ignore if table/column doesn't exist
        }

        LoginHistory::create([
            'user_id' => $event->user->id,
            'ip_address' => $ip,
            'country' => $country,
            'city' => $city,
            'user_agent' => request()->userAgent(),
            'logged_in_at' => now(),
        ]);
    }

    private static function isPrivateIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            // If it is NOT a public IP, treat as private/reserved
            // But filter_var with these flags returns false for private/reserved — we invert logic
            // We'll do explicit checks instead for clarity below
        }

        // explicit private ranges
        if (preg_match('/^(127\.|10\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[0-1])\.)/', $ip)) {
            return true;
        }

        return false;
    }
}
