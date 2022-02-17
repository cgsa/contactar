<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telefono implements Rule
{  
    

    public static function rule()
    {
        return new static;
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
        return $this->validation($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has inavlid characters.';
    }


    private function validation($telefono)
    {
        $str = str_replace(['+','-','/'], '', $telefono);
        return is_numeric(trim($str));
    }

}
