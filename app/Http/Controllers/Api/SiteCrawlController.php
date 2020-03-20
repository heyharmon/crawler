<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Page;
use App\Site;
use Carbon\Carbon;
use App\Jobs\Crawl;
use App\Http\Requests\SiteCrawlStoreRequest;


class SiteCrawlController extends Controller
{
    /**
     * Store.
     *
     * Crawl all pages of site matching URL provided.
     * A site with matching URL must already exist.
     */
    public function store(SiteCrawlStoreRequest $request)
    {

        // Validate request
        $validated = $request->validated();

        // Find this site in database
        $requested_site = Site::where('url', '=', $validated['url'])->firstOrFail();

        // TODO: Check that the site does not already have a crawl in progress

        // Delete the site
        // Site pages & failed pages will also be deleted
        $requested_site->delete();

        // Create new site
        $site = Site::create([
            'url' => $validated['url'],
        ]);

        // Create site's first new page
        $page = Page::create([
            'site_id'    => $site->id,
            'is_crawled' => false,
            'url'        => $site->url,
        ]);

        // Dispatch a job to crawl site pages
        $this->dispatch(new Crawl($page));

        // Set sites last crawl to now
        $site->last_crawl = Carbon::now()->format('Y-m-d H:i:s');
        $site->save();

        return response()->json('Site crawl in progress...');
    }
}