<?php

namespace C4n4r\Labour\Configuration\Infrastructure\Repository;

use C4n4r\Labour\Configuration\Domain\Exceptions\NoCountryFileException;
use C4n4r\Labour\Configuration\Domain\Model\PublicHoliday;
use C4n4r\Labour\Configuration\Domain\Repository\CountryPublicHolidayRepository;
use DateTime;

class CountryPublicHolidayRepositoryFS implements CountryPublicHolidayRepository
{

    /**
     * @throws NoCountryFileException
     */
    public function getPublicHolidaysPerYear(string $countryCode, int $year): array
    {
        $publicHolidays = [];
        $path = __DIR__ . '/../../../../resources/countries/' . $countryCode . '.json';
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            if(!isset($data[$year])) {
                throw new NoCountryFileException('Year not found');
            }
            foreach ($data[$year] as $publicHoliday) {
                $publicHolidays[] = new PublicHoliday(
                    $publicHoliday['label'],
                    $publicHoliday['localLabel'],
                    new DateTime($publicHoliday['date'])
                );
            }
        } else {
            throw new NoCountryFileException('Country not found');
        }
        return $publicHolidays;
    }
}