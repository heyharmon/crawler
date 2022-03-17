<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Site extends Model
{
    /**
     * Attributes to guard against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Appended Attributes.
     *
     * The casted attributes to append to the model's array form.
     */
    protected $appends = ['jobs_count', 'pages_count'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | This models relationships with other models
    |
    */

    /*
     * Define Pages relationship
     *
     */
    public function pages()
    {
        return $this->hasMany(\App\Page::class);
    }

    /*
     * Define Failed Pages relationship
     *
     */
    public function failedPages()
    {
        return $this->hasMany(\App\FailedPage::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    | The casted attributes to append to the model's array form.
    |
    */

    /*
     * Set 'last_crawl' attribute
     * Transform current database value to human readable
     *
     */
    public function getLastCrawlAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    /*
     * Set 'crawling_count' attribute
     * Append to the model's array form
     *
     */
    public function getJobsCountAttribute()
    {

        // Filter jobs by this site host
        // $jobs = \App\Jobs::all()->filter( function($job) {
        //     return $job->domain === $this->domain;
        // });
        $jobs = \App\Jobs::all()->filter( function($job) {
            return $job->host === $this->host;
        });

        return $jobs->count();
    }

    /*
     * Set 'pages_count' attribute
     * Append to the model's array form
     *
     */
    public function getPagesCountAttribute()
    {
        return $this->pages()->count();
    }
}
