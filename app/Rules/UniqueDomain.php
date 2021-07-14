<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

// Services
use App\Services\UrlService;

class UniqueDomain implements Rule
{
    public $domain;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->domain = UrlService::getDomain($url);
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
            ->where('domain', '=', $this->domain)
            ->exists();

        // Return false if site exists, failing validation
        return $page_exists === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Site with domain ' . $this->domain . ' already exists';
    }
}
