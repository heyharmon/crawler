<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Site;

class SitePagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Get site and its page, failed pages
        $site = Site::where('url', '=', request('url'))
            ->with('pages')
            ->with('failedPages')
            ->firstOrFail();

        return response()->json($site);

    }

}
