<?php

namespace C4n4r\Labour\Configuration\Domain\Exceptions;

use Exception;

class NoCountryFileException extends Exception
{
    public function __construct(string $countryCode)
    {
        parent::__construct(sprintf('No country file found for country code %s', $countryCode));
    }
}