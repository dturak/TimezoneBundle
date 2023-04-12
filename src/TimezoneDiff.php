<?php

namespace Appliora\Bundle\TimezoneBundle;

class TimezoneDiff
{
    private string $timezoneTrans = '';
    private array $units = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    public function __construct(private readonly Translators $translator) {}

    /**
     * @throws \Exception
     */
    public function timezoneDiff(\DateTimeInterface $dateTime, ?string $timezone = null, ?string $lang = null): string
    {
        if ($timezone !== null) {
            $dateTime = $dateTime->setTimezone(new \DateTimeZone($timezone));
        }

        return $this->setTimezoneDiff($dateTime, $lang);
    }

    /**
     * @throws \Exception
     */
    private function setTimezoneDiff(\DateTimeInterface $dateTime, ?string $lang): string
    {
        $this->timezoneTrans = $lang ? '**/*' . strtoupper($lang) : $dateTime->getTimezone()->getName() . '*';
        $timeDiff = $this->getDateWithTimezone($dateTime->getTimezone()->getName())->diff($dateTime);

        return $this->checkTime($timeDiff);
    }

    private function checkTime(\DateInterval $timeDiff): string
    {
        foreach ($this->units as $unitKey => $unitValue) {
            if ($timeDiff->$unitKey !== 0) {
                return $this->timezoneMessage([$timeDiff->invert ? 'ago' : 'in', $unitValue], $timeDiff->$unitKey);
            }
        }

        return $this->timezoneMessage(['now']);
    }

    /**
     * @throws \Exception
     */
    private function getDateWithTimezone(string $timezone): \DateTimeImmutable
    {
        $dateTime = new \DateTimeImmutable('NOW');
        $dateTime->setTimezone(new \DateTimeZone($timezone));

        return $dateTime;
    }

    private function timezoneMessage(array $keys, int $countTime = null): string
    {
        return $this->translator->translate($this->timezoneTrans, array_merge(['time'], $keys), $countTime);
    }
}