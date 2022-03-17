<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

// Services
use App\Services\UrlService;

class ExistingHost implements Rule
{
    public $host;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->host = UrlService::getHost($url);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $page_exists = DB::table('sites')
            ->where('host', '=', $this->host)
            ->exists();

        // Return true if site exists, passing validation
        return $page_exists === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Site with host ' . $this->host . ' does not exist';
    }
}
