<?php

use C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidays;
use C4n4r\Labour\Configuration\Domain\Exceptions\InvalidCountryCodeException;

function resource_path($string): string
{
    return __DIR__ . '/../../../resources/' . $string;
}

describe("Manage the way Labour handle countries", function() {

    beforeEach(function() {
        $this->configuration = ['FR'];
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

        $uc = new FindCountryPublicHolidays();

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

        $uc = new FindCountryPublicHolidays();

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
        $uc = new FindCountryPublicHolidays();
        $uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'FR', 2023
            )
        )->country;
        expect(file_exists(__DIR__ . '/../../../resources/countries/FR.json'))->toBeTrue();
    });

    it('should get UK information, we do not have any file, so it should ask API then write UK file', function () {
        $uc = new FindCountryPublicHolidays();
        $response = $uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'GB', 2023
            )
        )->country;
        expect($response->getPublicHolidays())->toBeArray()
            ->and($response->getPublicHolidays())->toHaveCount(16)
            ->and(file_exists(__DIR__ . '/../../../resources/countries/GB.json'))
            ->toBeTrue();
        unlink(resource_path('countries/GB.json'));
    });

    it('should trigger an invalid country code if API return an error', function () {
        $uc = new FindCountryPublicHolidays();
        expect($uc->handle(
            new C4n4r\Labour\Configuration\Application\UseCase\FindCountryPublicHolidaysInput(
                'XX', 2023
            )
        ))->toThrow(new InvalidCountryCodeException('XX'));
    });
});
