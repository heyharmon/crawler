<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Site;

// Requests
use App\Http\Requests\SiteRequest;
use App\Http\Requests\SiteStoreRequest;

// Services
use App\Services\UrlService;
use HeyHarmon\LaravelApify\Facades\Apify;

class SiteController extends Controller
{

    public function index()
    {
        // return Apify::add(5)->subtract(3)->result(); // 2

        // Get all sites
        $sites = Site::all();

        return response()->json($sites);
    }

    public function store(SiteStoreRequest $request)
    {
        $site = Site::create([
            // 'domain' => UrlService::getDomain($request['url'])
            'host' => UrlService::getHost($request['url'])
        ]);

        return response()->json($site);
    }

    public function show(SiteRequest $request)
    {
        // $urlService = new UrlService;
        // return $urlService->getAll($request['url']);

        // Find this site in database
        // $site = Site::where('domain', '=', UrlService::getDomain($request['url']))
        //     ->firstOrFail();
        $site = Site::where('host', '=', UrlService::getHost($request['url']))
            ->firstOrFail();

        return response()->json($site);
    }

    public function destroy(SiteRequest $request)
    {
        // Find this site in database
        // $site = Site::where('domain', '=', UrlService::getDomain($request['url']))
        //     ->firstOrFail();
        $site = Site::where('host', '=', UrlService::getHost($request['url']))
            ->firstOrFail();

        // Delete
        $site->delete();

        return response()->json($site);
    }
}
