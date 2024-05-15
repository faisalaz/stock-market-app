<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class SymbolExists implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $response = Http::get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
            $symbols = $response->json();
            
            // Extract symbol values from the response
            $symbolValues = collect($symbols)->pluck('Symbol')->toArray();

            return in_array($value, $symbolValues);
        } catch (\Exception $e) {
            // Log the exception or handle it appropriately
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected symbol is invalid.';
    }
}
