<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedPage extends Model
{

    /**
     * Attributes to guard against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /*
     * Define Site relationship.
     *
     */
    public function site()
    {
        return $this->belongsTo(\App\Site::class);
    }

}
