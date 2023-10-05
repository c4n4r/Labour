<?php

namespace C4n4r\Labour\Configuration\Domain\Exceptions;

use Exception;

class InvalidCountryCodeException extends Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct("Invalid country code: {$string}");
    }
}