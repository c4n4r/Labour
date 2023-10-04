<?php

namespace C4n4r\Labour\Configuration\Application\UseCase;

class FindCountryPublicHolidaysInput
{

    public function __construct(
        public string $countryCode,
        public int $year
    ){}

}