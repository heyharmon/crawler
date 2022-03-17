<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Packages
use Carbon\Carbon;

// Models
use App\Page;
use App\Site;

// Requests
use App\Http\Requests\SiteCrawlStoreRequest;

// Services
use App\Services\UrlService;

// Jobs
use App\Jobs\Crawl;

class SiteCrawlController extends Controller
{

    /**
     * Crawl a page
     *
     */
    public function store(SiteCrawlStoreRequest $request)
    {
        $urlService = new UrlService();

        // Find this site
        // $site = Site::where('domain', '=', UrlService::getDomain($request['url']))
        //     ->firstOrFail();
        $site = Site::where('host', '=', UrlService::getHost($request['url']))
            ->firstOrFail();

        // Find page or create it
        $page = Page::firstOrCreate(
            ['url' => $request['url']],
            [
                'is_crawled' => false,
                'site_id'    => $site->id,
                'url'        => $request['url'],
            ]
        );

        // Crawl the page
        Crawl::dispatch($page);

        // Set sites' last crawl to now
        // $site->last_crawl = Carbon::now()->format('Y-m-d H:i:s');

        // Save page
        // $site->save();

        return response()->json([
            'message' => 'Crawl in progress.',
            'page' => $page
        ]);
    }
}
