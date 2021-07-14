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
use App\Services\UrlParser\ParserService;

// Jobs
use App\Jobs\Crawl;

class SiteCrawlController extends Controller
{

    /**
     * Store.
     *
     * Crawl all pages of site matching domain provided.
     * A site with matching domain must already exist.
     */
    public function store(SiteCrawlStoreRequest $request)
    {
        // Find this site in database
        $site = Site::where('host', '=', $this->host)
            ->firstOrFail();

        // TODO: Check that the site does not already have a crawl in progress

        // Delete the site
        // Site pages & failed pages will also be deleted
        $site->delete();

        // Create new site
        $site = Site::create([
            'host' => $this->host,
        ]);

        // Create page
        $page = Page::create([
            'site_id'    => $site->id,
            'is_crawled' => false,
            'url'        => $request['url'],
        ]);

        // Dispatch a job to crawl site pages
        $this->dispatch(new Crawl($page));

        // Set sites' last crawl to now
        $site->last_crawl = Carbon::now()->format('Y-m-d H:i:s');
        $site->save();

        return response()->json('Site crawl in progress...');
    }
}
