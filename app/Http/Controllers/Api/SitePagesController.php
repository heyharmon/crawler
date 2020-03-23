<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Site;

class SitePagesController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Get site and its page, failed pages
        $site = Site::where('host', '=', $this->host)
            ->with('pages')
            ->with('failedPages')
            ->firstOrFail();

        return response()->json($site);

    }

}
