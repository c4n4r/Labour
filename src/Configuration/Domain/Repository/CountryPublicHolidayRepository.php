<?php

namespace C4n4r\Labour\Configuration\Domain\Repository;

interface CountryPublicHolidayRepository
{
    public function getPublicHolidaysPerYear(string $countryCode, int $year): array;
}