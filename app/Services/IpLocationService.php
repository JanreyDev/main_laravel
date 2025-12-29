<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class IpLocationService
{
    /**
     * Get the user's real public IP address
     * Even if they're behind a router/NAT
     */
    public function getRealPublicIp()
    {
        // Try to get from cache first (cache for 5 minutes)
        return Cache::remember('user_public_ip', 300, function () {

            // Try multiple services in case one is down
            $services = [
                'https://api.ipify.org?format=json',
                'https://api.my-ip.io/ip.json',
                'https://ipapi.co/json/',
            ];

            foreach ($services as $service) {
                try {
                    $response = Http::timeout(3)->get($service);

                    if ($response->successful()) {
                        $data = $response->json();

                        // Different services return IP in different fields
                        $ip = $data['ip'] ?? $data['query'] ?? null;

                        if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                            return $ip;
                        }
                    }
                } catch (\Exception $e) {
                    // Try next service
                    continue;
                }
            }

            // If all services fail, return null
            return null;
        });
    }

    /**
     * Get IP address for location lookup
     * Smart detection: uses real public IP if available, falls back to test IP in dev
     */
    public function getIpForLocation($requestIp)
    {
        // Check if request IP is private
        $isPrivateIp = $this->isPrivateIp($requestIp);

        if ($isPrivateIp) {
            // In development, try to get real public IP
            if (config('app.env') === 'local') {
                $publicIp = $this->getRealPublicIp();

                if ($publicIp) {
                    return [
                        'ip' => $publicIp,
                        'is_test' => false,
                        'is_real_public' => true,
                        'local_ip' => $requestIp
                    ];
                }

                // Fallback to test IP if we can't get real public IP
                return [
                    'ip' => '203.177.4.11',
                    'is_test' => true,
                    'is_real_public' => false,
                    'local_ip' => $requestIp
                ];
            }
        }

        // Use request IP as-is (production with public IP)
        return [
            'ip' => $requestIp,
            'is_test' => false,
            'is_real_public' => !$isPrivateIp,
            'local_ip' => null
        ];
    }

    /**
     * Check if IP is private/local
     */
    private function isPrivateIp($ip)
    {
        return preg_match('/^(127\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.|192\.168\.)/', $ip) === 1;
    }

    /**
     * Get multiple test IPs for different regions
     */
    public function getTestIps()
    {
        return [
            'philippines' => '203.177.4.11',
            'usa' => '8.8.8.8',
            'japan' => '13.113.196.224',
            'uk' => '81.2.69.142',
            'australia' => '1.1.1.1',
        ];
    }

    /**
     * Allow user to select test location in development
     */
    public function getTestIpByRegion($region = 'philippines')
    {
        $testIps = $this->getTestIps();
        return $testIps[$region] ?? $testIps['philippines'];
    }
}