<?php

namespace C4n4r\Labour\Configuration\Domain\Model;



use DateTime;

class PublicHoliday
{

    private string $label;
    private string $localLabel;
    private DateTime $date;


    public function __construct(string $label, string $localLabel, DateTime $date)
    {
        $this->label = $label;
        $this->localLabel = $localLabel;
        $this->date = $date;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLocalLabel(): string
    {
        return $this->localLabel;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getYear(): int
    {
        return $this->date->format('Y');
    }

    public function getMonth(): int
    {
        return $this->date->format('m');
    }

    public function getDay(): int
    {
        return $this->date->format('d');
    }

    public function getWeekDay(): int
    {
        return $this->date->format('w');
    }

    public function getWeekNumber(): int
    {
        return $this->date->format('W');
    }

    public function getQuarter(): int
    {
        return ceil($this->date->format('m') / 3);
    }

    public function getQuarterName(): string
    {
        return 'Q' . $this->getQuarter();
    }

    public function getSemester(): int
    {
        return ceil($this->date->format('m') / 6);
    }

    public function getSemesterName(): string
    {
        return 'S' . $this->getSemester();
    }

    public function getTrimester(): int
    {
        return ceil($this->date->format('m') / 4);
    }

    public function getTrimesterName(): string
    {
        return 'T' . $this->getTrimester();
    }

    public function getSeason(): string
    {
        $seasons = [
            'winter' => [12, 1, 2],
            'spring' => [3, 4, 5],
            'summer' => [6, 7, 8],
            'autumn' => [9, 10, 11],
        ];
        $month = $this->date->format('m');
        foreach ($seasons as $season => $months) {
            if (in_array($month, $months)) {
                return $season;
            }
        }
        return 'unknown';
    }

    public function getSeasonName(): string
    {
        return ucfirst($this->getSeason());
    }

    public function isWeekend(): bool
    {
        return in_array($this->getWeekDay(), [0, 6]);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'localLabel' => $this->getLocalLabel(),
            'date' => $this->getDate()->format('Y-m-d')
        ];
    }
}