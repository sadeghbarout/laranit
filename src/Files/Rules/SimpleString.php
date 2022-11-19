<?php

namespace App\Rules;

use App\Extras\Validator;
use Illuminate\Contracts\Validation\Rule;

class SimpleString implements Rule
{
	var $strict=false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($strict=false)
    {
    	$this->strict=$strict;
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
        //

		if($this->strict){
			$pattern = Validator::string_strict_regex;
		}else{
			$pattern = Validator::string_regex;
		}
		if(preg_match(ltrim($pattern,'regex:'),$value)){
			return true;
		}
		return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute  نباید شامل کاراکتر های خاص باشد(مانند: #%&;<> ,...) ";
    }
}
