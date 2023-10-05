<?php

namespace C4n4r\Labour\Configuration\Application\UseCase;

use C4n4r\Labour\Configuration\Domain\Model\Country;

class FindCountryPublicHolidaysOutput
{

    public function __construct(
        public Country $country
    ){}

}