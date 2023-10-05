<?php

namespace C4n4r\Labour\Configuration\Infrastructure\Repository;

use C4n4r\Labour\Configuration\Domain\Exceptions\InvalidCountryCodeException;
use C4n4r\Labour\Configuration\Domain\Model\PublicHoliday;
use C4n4r\Labour\Configuration\Domain\Repository\CountryPublicHolidayRepository;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CountryPublicHolidayRepositoryFetch implements CountryPublicHolidayRepository
{

    private Client $httpClient;
    public function __construct()
    {
        $this->httpClient = new Client(
            [
                'base_uri' => 'https://date.nager.at/api/v3/publicholidays/'
            ]
        );
    }

    /**
     * @param string $countryCode
     * @param int $year
     * @return PublicHoliday[]
     * @throws InvalidCountryCodeException|GuzzleException
     */
    public function getPublicHolidaysPerYear(string $countryCode, int $year): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $year . '/' . $countryCode
            );
        } catch (Exception $e) {
            throw new InvalidCountryCodeException($countryCode);
        }

        $publicHolidays = [];
        $data = json_decode($response->getBody()->getContents(), true);
        foreach ($data as $publicHoliday) {
            $publicHolidays[] = new PublicHoliday(
                $publicHoliday['name'],
                $publicHoliday['localName'],
                new DateTime($publicHoliday['date'])
            );
        }
        return $publicHolidays;

    }
}