<?php

namespace C4n4r\Labour\Configuration\Application\UseCase;

use C4n4r\Labour\Configuration\Domain\Exceptions\NoCountryFileException;
use C4n4r\Labour\Configuration\Domain\Model\Country;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFetch;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFS;
use function array_map;

class FindCountryPublicHolidays
{

    public function __construct(
        private readonly CountryPublicHolidayRepositoryFS $countryPublicHolidayRepositoryFS,
        private readonly CountryPublicHolidayRepositoryFetch $countryPublicHolidayRepositoryFetch
    )
    {
    }


    public function handle(FindCountryPublicHolidaysInput $input): FindCountryPublicHolidaysOutput
    {

        try {
            $holidays = $this->countryPublicHolidayRepositoryFS->getPublicHolidaysPerYear(
                $input->countryCode, $input->year
            );
        } catch (NoCountryFileException $exception) {
            $holidays = $this->countryPublicHolidayRepositoryFetch->getPublicHolidaysPerYear(
                $input->countryCode, $input->year
            );
            $data = [];
            $data[$input->year] = array_map(function ($holiday) {
                return $holiday->toArray();
            }, $holidays);

            $path = __DIR__ . '/../../../../resources/countries/' . $input->countryCode . '.json';
            fopen($path, 'w');
            file_put_contents($path, json_encode($data));
        }

        return new FindCountryPublicHolidaysOutput(
                new Country($input->countryCode, $holidays)
        );


    }

}