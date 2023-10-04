<?php

use C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidays;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFetch;
use C4n4r\Labour\Configuration\Infrastructure\Repository\CountryPublicHolidayRepositoryFS;

function resource_path($string): string
{
    return __DIR__ . '/../../../resources/' . $string;
}

describe("Manage the way Labour handle countries", function() {

    beforeEach(function() {
        $this->configuration = ['FR'];

        //create file for FR
        $file = fopen(resource_path('countries/FR.json'), 'w');
        $data = file_get_contents(__DIR__ . '/mock/FR.json');
        fwrite($file, $data);
    });

    afterEach(function() {
        unlink(resource_path('countries/FR.json'));
    });

    it("should list all files in the resources/countries folder", function() {
        $files = scandir(resource_path('countries'));
        expect($files)->toBeArray();
    });

    it("should list all public holidays in resources/countries/FR.json", function() {

        $uc = new FindCountryPublicHolidays(
            new CountryPublicHolidayRepositoryFS(),
            new CountryPublicHolidayRepositoryFetch()
        );

        $country = $uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'FR', 2023
            )
        )->country;
        expect($country->getPublicHolidays())->toBeArray()
            ->and($country->getPublicHolidays())->toHaveCount(11);
    });

    it('should fetch data from API if there is no data in the json file', function () {

        unlink(resource_path('countries/FR.json'));

        $uc = new FindCountryPublicHolidays(
            new CountryPublicHolidayRepositoryFS(),
            new CountryPublicHolidayRepositoryFetch()
        );

        $response = $uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'FR', 2023
            )
        )->country;
        expect($response->getPublicHolidays())->toBeArray()
            ->and($response->getPublicHolidays())->toHaveCount(11);
    });

    it('should save data in file after api fetch', function () {

        unlink(resource_path('countries/FR.json'));

        $uc = new FindCountryPublicHolidays(
            new CountryPublicHolidayRepositoryFS(),
            new CountryPublicHolidayRepositoryFetch()
        );

        $response = $uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'FR', 2023
            )
        )->country;

        expect(file_exists(__DIR__ . '/../../../resources/countries/FR.json'))->toBeTrue();

    });

});
