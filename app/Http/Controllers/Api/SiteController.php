<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Site;

// Requests
use App\Http\Requests\SiteDestroyRequest;
use App\Http\Requests\SiteStoreRequest;
use App\Http\Requests\SiteUpdateRequest;

// Services
use App\Services\UrlParser\ParserService;

class SiteController extends Controller
{

    /**
     * Public variables.
     */
    public $parsed_url;

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        // Set up Url parser service
        $parser_service = new ParserService($request['url']);

        // Set up parsed url
        $this->parsed_url = $parser_service->parseUrl();

    }

    /**
     * Get all.
     */
    public function index()
    {

        // Get all sites
        $sites = Site::all();

        return response()->json($sites);

    }

    /**
     * Store new item in database.
     */
    public function store(SiteStoreRequest $request)
    {

        // Validate request
        $validated = $request->validated();

        // Store site
        $site = Site::firstOrCreate([
            'domain' => $this->parsed_url['domain'],
        ]);

        return response()->json($site);

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

        // Find this site in database
        $site = Site::where('domain', '=', $this->parsed_url['domain'])->firstOrFail();

        return response()->json($site);

    }

    /**
     * Remove item from database.
     */
    public function destroy(SiteDestroyRequest $request)
    {

        // Validate request
        $validated = $request->validated();

        // Find this site in database
        $site = Site::where('domain', '=', $this->parsed_url['domain'])->firstOrFail();

        // Delete
        $site->delete();

        return response()->json($site);

    }

}
