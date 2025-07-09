<?php

namespace App\Http\Middleware;

use App\Models\Analytics\Browser;
use App\Models\Analytics\Country;
use App\Models\Analytics\IpAddress;
use App\Models\Analytics\OperatingSystem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LogAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $serverData = $request->server();
        // $ip = $request->ip();
        $ip = '183.82.30.12';

        dd($request->userAgent());

        $response = Http::get('http://ip-api.com/json/'.$ip.'?fields=status,message,country,countryCode,regionName,city,isp');

        $region = 'Local';
        $city = 'Local';
        $isp = 'ISP';
        $countryCode = 'LO';
        $country = 'LOCAL';

        if($response->ok()){
            $data = $response->json();

            $region = $data['regionName'];
            $city = $data['city'];
            $isp = $data['isp'];
            $countryCode = $data['countryCode'];
            $country = $data['country'];
        }

        $country = Country::firstOrNew([
            ['country_name' => $countryCode],
            ['country_name' => $country]
        ]);

        $ipaddress = IpAddress::firstOrNew([
            ['ip_address' => $ip],
            [
                'ip_address_hash' => hash('md5', $ip),
                'region' => $region,
                'city' => $city,
                'isp' => $isp,
                'country_id' => $country->id,
            ]
        ]);

        // $browser = Browser::firstOrNew([
        // ['browser_name' => 1,
        // 'browser_version' => 1],
        // ['browser_major_version'],
        // ]);

        // $os = OperatingSystem::firstOrNew([
        // ['os_name',
        // 'os_version'],
        // ['os_major_version']
        // ]);

        dd($serverData);

        return $next($request);
    }
}
