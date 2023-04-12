<?php

namespace Appliora\Bundle\TimezoneBundle\Twig\Extension;

use Appliora\Bundle\TimezoneBundle\TimezoneDiff;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeExtension extends TwigExtension
{
    public function __construct(protected TimezoneDiff $dateTimeDiff) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('timezone_diff', [$this, 'timezoneDiff'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('diff', [$this, 'timezoneDiff'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws \Exception
     */
    public function timezoneDiff(\DateTimeInterface $dateTime, ?string $timezone = null, ?string $lang = null): string
    {
        return $this->dateTimeDiff->timezoneDiff($dateTime, $timezone, $lang);
    }
}