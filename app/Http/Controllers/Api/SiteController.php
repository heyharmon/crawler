<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Site;
use App\Http\Requests\SiteDestroyRequest;
use App\Http\Requests\SiteStoreRequest;
use App\Http\Requests\SiteUpdateRequest;

class SiteController extends Controller
{

    /**
     * Public variables.
     */
    public $scheme;
    public $host;

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        if ($request['url']) {
            $this->scheme = parse_url($request['url'])['scheme']; // e.g., http(s)
            $this->host = parse_url($request['url'])['host']; // e.g., heyharmon.com
        }

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
        $site = Site::create([
            'scheme' => $this->scheme,
            'host' => $this->host,
        ]);

        return response()->json($site);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        // Find this site in database
        $site = Site::where('host', '=', $this->host)->firstOrFail();

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
        $site = Site::where('host', '=', $this->host)->firstOrFail();

        // Delete
        $site->delete();

        return response()->json($site);

    }

}
