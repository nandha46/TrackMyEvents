<?php

namespace App\Http\Middleware;

use App\Models\Analytics\Browser;
use App\Models\Analytics\Country;
use App\Models\Analytics\IpAddress;
use App\Models\Analytics\OperatingSystem;
use App\Models\Analytics\Page;
use App\Models\Analytics\PageView;
use App\Models\Analytics\Referrer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use WhichBrowser\Parser;

class LogAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $ip = $request->ip();
        $ip = '183.82.30.12';

        $response = Http::get('http://ip-api.com/json/' . $ip . '?fields=status,message,country,countryCode,regionName,city,isp');

        $region = 'Local';
        $city = 'Local';
        $isp = 'ISP';
        $countryCode = 'LO';
        $country = 'LOCAL';

        if ($response->ok()) {
            $data = $response->json();

            $region = $data['regionName'];
            $city = $data['city'];
            $isp = $data['isp'];
            $countryCode = $data['countryCode'];
            $country = $data['country'];
        }

        $country = Country::firstOrCreate(
            ['country_code' => $countryCode],
            ['country_name' => $country]
        );

        $ipaddress = IpAddress::firstOrCreate(
            ['ip_address' => $ip],
            [
                'ip_address_hash' => hash('md5', $ip),
                'region' => $region,
                'city' => $city,
                'isp' => $isp,
                'country_id' => $country->id,
            ]
        );

        $parsedUA = new Parser($request->userAgent());

        $browser = Browser::firstOrCreate(
            [
                'browser_name' => $parsedUA->browser->name,
                'browser_version' => $parsedUA->browser->version->value
            ]
        );

        $os = OperatingSystem::firstOrCreate(
            [
                'os_name' => $parsedUA->os->name,
                'os_version' => $parsedUA->os->version->alias
            ]
        );

        //TO-DO parse referer url to identify referer type & domain
        $referrer = Referrer::firstOrCreate([
            'referrer_url' => $request->header('referer', "Direct"),
        ], [
            'referrer_domain' => $request->header('referer', "Direct"),
            'referrer_type' => 'TBD',
        ]);

        $page = Page::firstOrCreate([
            'url_path' => $request->url()
        ]);

        PageView::create([
            'device_type' => $parsedUA->device->type,
            'user_id' => null,
            'page_id' => $page->id,
            'referrer_id' => $referrer->id,
            'ip_address_id' => $ipaddress->id,
            'browser_id' => $browser->id,
            'os_id' => $os->id,
        ]);

        return $next($request);
    }
}
