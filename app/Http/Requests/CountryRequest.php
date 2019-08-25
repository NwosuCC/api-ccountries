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
        // Get Country instance injected via the route param {country}
        // $country is available e.g for edit where the route contains param {country}
        // Edit   => GET  '/countries/{country}/edit'
        // Update => POST '/countries/{country}'
        $country = $this->route('country');

        return [
            'name' => [
                'required', 'string',
                // For update, ignore the current $country 'id' during unique check in DB table
                Rule::unique('countries')->whereNull('deleted_at')->ignore($country ? $country->id : ''),
            ],
            'continent' => [
                'string',
                Rule::exists('continents', 'name'),
            ],
        ];
    }
}
