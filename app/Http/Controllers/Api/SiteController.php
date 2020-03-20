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
            'url' => $validated['url'],
        ]);

        return response()->json($site);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        // Find this site in database
        $site = Site::where('url', '=', request('url'))->firstOrFail();

        return response()->json($site);

    }

    /**
     * Update item in database.
     */
    public function update(SiteUpdateRequest $request)
    {

        // Validate request
        $validated = $request->validated();

        // Find this site in database
        $site = Site::where('url', '=', $validated['url'])->firstOrFail();

        // Update
        $site->update([
            'url' => $validated['new_url'],
        ]);

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
        $site = Site::where('url', '=', $validated['url'])->firstOrFail();

        // Delete
        $site->delete();

        return response()->json($site);

    }

}
