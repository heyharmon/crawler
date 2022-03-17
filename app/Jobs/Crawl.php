<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// Facades
use Illuminate\Support\Facades\Http;

// Models
use App\Page;
use App\FailedPage;

// Services
use App\Services\UrlService;

class Crawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Public variables.
     */
    public $page;

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get page
        // $response = Http::get('http://localhost:5001/bloomcu-scraping-functions/us-central1/cheerio/page', [
        $response = Http::get('https://us-central1-bloomcu-scraping-functions.cloudfunctions.net/cheerio/page', [
            'url' => $this->page->url
        ])->json();

        // Fail job
        if ($response['status'] !== 200) {
            $this->page->update([
                'is_crawled' => true,
                'status'     => $response['status'],
            ]);

            return true;
        }

        // Update page
        $this->page->update([
            'is_crawled' => true,
            'status'     => $response['status'],
            'title'      => $response['title'],
            'wordcount'  => $response['wordcount'],
            'body'       => $response['body']
        ]);

        // Iterate over each link
        foreach ($response['links'] as $link) {
            $linkHost = UrlService::getHost($link['url']);

            // TODO: Right an action that processes the page via a pipeline:
            if (
                $linkHost === UrlService::getHost($this->page->url) && // Host matches site
                !Page::where('url', $link['url'])->exists() // Doesn't already exist
            ) {
                $page = Page::create([
                    'site_id'    => $this->page->site_id,
                    'type'       => $link['type'],
                    'url'        => $link['url'],
                    'is_crawled' => false
                ]);

                // Crawl it
                if ($link['type'] === 'link') {
                    dispatch(new self($page));
                }
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed($exception)
    {
        // switch ($exception) {
        //     case strpos($exception, 'MaxAttemptsExceededException') !== false:
        //         $exception = 'No response';
        //         break;
        //     default:
        //         $exception = 'Failed';
        // }
        //
        // $page = FailedPage::create([
        //     'site_id' => $this->page->site_id,
        //     'url' => $this->page->url,
        //     'exception' => $exception,
        // ]);
    }
}
