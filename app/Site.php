<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * Set 'crawling_count' attribute
     *
     */
    public function getJobsCountAttribute()
    {
        $jobs = \App\Jobs::all()->filter( function($job) {
            return $job->host === 'heyharmon.com';
        });

        return $jobs->count();
    }

    /*
     * Set 'pages_count' attribute
     *
     */
    public function getPagesCountAttribute()
    {
        return $this->pages()->count();
    }
}
