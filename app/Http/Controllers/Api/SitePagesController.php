<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Site;

// Services
use App\Services\UrlService;

class SitePagesController extends Controller
{

    public function index(Request $request)
    {
        // Parse domain from url
        // $domain = UrlService::getDomain($request['url']);
        $host = UrlService::getHost($request['url']);

        // Get site and its pages, plus failed pages
        // $site = Site::where('domain', '=', $domain)
        //     ->with('pages')
        //     // ->with('failedPages')
        //     ->firstOrFail();
        $site = Site::where('host', '=', $host)
            ->with('pages')
            // ->with('failedPages')
            ->firstOrFail();

        return response()->json($site);
    }

}
