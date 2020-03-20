<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Attributes to guard against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | This models relationships with other models
    |
    |
    */

    /*
     * Define Site relationship.
     *
     */
    public function site()
    {
        return $this->belongsTo(\App\Site::class);
    }

}
