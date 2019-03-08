<?php

namespace App\Http\Requests;

use App\Continent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Implemented in CountryPolicy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $country = $this->route('country');

        return [
            'name' => [
                'required', 'string',
                Rule::unique('countries')->whereNull('deleted_at')->ignore($country ? $country->id : ''),
            ],
            'continent' => [
                'string',
                Rule::exists('continents', 'name'),
            ],
        ];
    }
}
