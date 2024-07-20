<?php

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;

class WhitelistedEmailDomain implements Rule
{
    public function passes($attribute, $value)
    {
        return in_array(\Str::after($value, '@'), ['tighten.co']);
    }

    public function message()
    {
        return 'The :attribute field is not from a whitelisted email provider';
    }
}
