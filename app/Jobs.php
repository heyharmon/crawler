<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
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
    protected $appends = ['host'];

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
    public function getHostAttribute()
    {

        $payload = json_decode($this->payload, true);
        $command = unserialize($payload['data']['command']);
        $host = $command->host;

        return $host;

    }

}
