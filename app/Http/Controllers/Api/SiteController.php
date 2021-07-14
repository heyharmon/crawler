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

class SiteController extends Controller
{

    public function index()
    {
        // Get all sites
        $sites = Site::all();

        return response()->json($sites);
    }

    public function store(SiteStoreRequest $request)
    {
        $site = Site::create([
            'domain' => UrlService::getDomain($request['url'])
        ]);

        return response()->json($site);
    }

    public function show(SiteRequest $request)
    {
        // Find this site in database
        $site = Site::where('domain', '=', UrlService::getDomain($request['url']))
            ->firstOrFail();

        return response()->json($site);
    }

    public function destroy(SiteRequest $request)
    {
        // Find this site in database
        $site = Site::where('domain', '=', UrlService::getDomain($request['url']))
            ->firstOrFail();

        // Delete
        $site->delete();

        return response()->json($site);
    }
}
