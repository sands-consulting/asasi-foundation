<?php

namespace Sands\Asasi\Foundation\Http\Exceptions;

use Exception;

class RuleNotExists extends Exception
{
    public function __construct($class, $code = 0, Exception $previous = null)
    {
        $this->message = 'Validation rules for "'.$class.'" does not exists';
        parent::__construct($this->message, $code = 0, $previous);
    }
}
