<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// Packages
use Goutte;

// Models
use App\Page;
use App\FailedPage;

// Services
use App\Services\UrlParser\ParserService;

class Crawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    // /**
    //  * Public variables.
    //  */
    // public $page;
    // public $domain;
    // public $path;
    // // public $scheme;
    // // public $host;
    //
    // /**
    //  * Create a new job instance.
    //  *
    //  * @return void
    //  */
    // public function __construct($page, UrlParsingService $service)
    // {
    //
    //     $this->page = $page;
    //     $this->domain = $service->getDomain($request['url']);
    //     $this->path = isset(parse_url($this->page->url)['path']) ? parse_url($this->page->url)['path'] : '/';
    //     // $this->scheme = parse_url($this->page->url)['scheme']; // e.g., http(s)
    //     // $this->host = parse_url($this->page->url)['host']; // e.g., heyharmon.com
    //
    // }

    /**
     * Public variables.
     */
    public $page;
    public $parsed_url;

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct($page)
    {

        // Set up this page
        $this->page = $page;

        // Set up Url parser service
        $parser_service = new ParserService($page->url);

        // Set up parsed url
        $this->parsed_url = $parser_service->parseUrl();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->processCurrentUrl();

    }

    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed($exception)
    {

        switch ($exception) {
            case strpos($exception, 'MaxAttemptsExceededException') !== false:
                $exception = 'No response';
                break;
            default:
                $exception = 'Failed';
        }

        $page = FailedPage::create([
            'site_id' => $this->page->site_id,
            'url' => $this->page->url,
            'exception' => $exception,
        ]);

    }

    /**
     * Get all links on page.
     */
    protected function processCurrentUrl()
    {

        // Visit page
        $crawler = Goutte::request('GET', $this->page->url);

        // Iterate over each link on page
        $crawler->filter('a')->each(function ($node) {

            // Get clean url
            $url = $node->attr('href'); //E.g., 'https://heyharmon.com/auto-loan/#foo-bar'
            $url = $this->normalizeUrl($url); //Result, 'https://heyharmon.com/auto-loan'

            // Is href valid
            if ($this->isValidUrl($url)) {

                // Get path
                $path = parse_url($url)['path'];

                // Persist new page to database
                $page = Page::create([
                    'site_id' => $this->page->site_id,
                    'url' => $url,
                    'path' => $path,
                    'is_crawled' => false,
                ]);

                // Dispatch a job for processing this page
                dispatch(new self($page));
            }
        });

        /*
        * Finish handeling this URL
        *
        */

        // Update status to crawled
        $this->page->is_crawled = true;

        // Update http status
        $this->page->status = $this->getHttpStatus($this->page->url);

        // If page title can be found
        if ($crawler->filter('html > head > title')->count() > 0) {

            // Update page title
            $this->page->title = $crawler->filter('html > head > title')->text();
        }

        // Persist updates to database
        $this->page->save();
    }

    protected function isValidUrl($url)
    {

        // Set up Url parser service
        $parser_service = new ParserService($url);

        // Set up parsed url
        $parsed_url = $parser_service->parseUrl();

        // Url has a host
        if (isset($parsed_url['host'])) {

            // If provided URL matches this domain
            // and scheme matches this scheme
            // and does not already exist in the database
            if (
                $parsed_url['domain'] === $this->parsed_url['domain'] &&
                !Page::where('path', $parsed_url['path'])->exists()
                // strpos($this->page->url, $parsed_url['host']) !== false &&
                // strpos($this->scheme, $parsed_url['scheme']) !== false &&
                // !Page::where('url', $url)->exists()
                )
            {
                return true;
            }
        }

        return false;

    }

    protected function normalizeUrl($url)
    {

        // Normalize empty spaces
        $url = str_replace(' ', '%20', $url);

        // Remove everything after "#"
        $url = explode('#', $url)[0];

        // Remove everything after "?"
        $url = explode('?', $url)[0];

        // Trim "/" from front
        $url = rtrim($url, '/');

        // Get the url's host, path, etc
        $parsed_url = parse_url($url);

        // If no host (url is relative, e.g., "/auto-loan")
        if (! isset($parsed_url['host'])) {

            // Concatenate this domain with url
            $url = $this->parsed_url['scheme'] . '://' . $this->parsed_url['host'] . $url;
        }

        return $url;

    }

    protected function getHttpStatus($url)
    {

        if (get_headers($url, 1)) {
            $headers = get_headers($url, 1);
            return intval(substr($headers[0], 9, 3));
        } else {
            return NULL;
        }

    }

}
