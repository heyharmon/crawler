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

class EvaluateJob implements ShouldQueue
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
        $response = Http::get('firebase-endpoint-url', [
            'url' => $this->page->url
        ])->json();

        // Update page
        // $this->page->update([
        //     'is_crawled' => true,
        //     'status'     => $response['status'],
        //     'title'      => $response['title'],
        //     'wordcount'  => $response['wordcount'],
        //     'body'       => $response['body']
        // ]);

        // Iterate over each violation
        foreach ($response['violation'] as $violations) {
            $violation = Violations::findOrCreate($violation_id, $violation);

            $this->page->violations()->sync($violation->violation_id);
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
