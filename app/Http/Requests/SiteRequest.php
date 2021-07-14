<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\ApiRequest;

class SiteRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'url|required',
        ];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'url.url' => 'Must be a valid url. E.g., https://google.com',
            'url.required' => 'Url is required',
        ];
    }
}
