<?php

namespace C4n4r\Labour\Configuration\Domain\Model;
class Country
{

    private string $code;
    /**
     * @var PublicHoliday[] $publicHolidays
     */
    private array $publicHolidays;

    public function __construct(string $code, array $publicHolidays)
    {
        $this->code = $code;
        $this->publicHolidays = $publicHolidays;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPublicHolidays(): array
    {
        return $this->publicHolidays;
    }
}