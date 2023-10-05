<?php

namespace C4n4r\Labour\Configuration\Infrastructure\Factory;

use C4n4r\Labour\Configuration\Domain\Repository\CountryPublicHolidayRepository;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFetch;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFS;

class CountryPublicHolidayRepositoryFactory
{
    public static function create(string $type): CountryPublicHolidayRepository {
        return match ($type) {
            'file' => new CountryPublicHolidayRepositoryFS(),
            'api' => new CountryPublicHolidayRepositoryFetch(),
        };
    }
}