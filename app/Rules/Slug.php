<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // If has Underscores
        if (preg_match('/_/', $value)) {
            $this->message = trans('validation.no_underscores');

            return false;
        }

        // If starts with dashes
        if (preg_match('/^-/', $value)) {
            $this->message = trans('validation.no_starting_dashes');

            return false;
        }

        // If finished with dashes
        if (preg_match('/-$/', $value)) {
            $this->message = trans('validation.no_finish_dashes');

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
