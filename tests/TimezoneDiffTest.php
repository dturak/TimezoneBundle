<?php

namespace Appliora\Bundle\TimezoneBundle\Tests;

use Appliora\Bundle\TimezoneBundle\TimezoneDiff;
use Appliora\Bundle\TimezoneBundle\Translators;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Translator;

class TimezoneDiffTest extends TestCase
{
    private TimezoneDiff $diff;

    protected function setUp(): void
    {
        $translator = new Translator('');
        $parseFile = new Translators($translator);
        $this->diff = new TimezoneDiff($parseFile);
    }

    /**
     * @throws \Exception
     */
    public function testTimezoneNowMessageForWarsaw()
    {
        $dateTime = new \DateTimeImmutable('Now');
        $diffMessage = $this->diff->timezoneDiff($dateTime, 'Europe/Warsaw');

        $this->assertEquals('teraz', $diffMessage);
    }

    /**
     * @throws \Exception
     */
    public function testTimezoneNowMessageWithDiffTimezoneTrans()
    {
        $dateTime = new \DateTimeImmutable('Now');
        $dateTime = $dateTime->setTimezone(new \DateTimeZone('Europe/Warsaw'));
        $diffMessage = $this->diff->timezoneDiff($dateTime, 'Europe/Berlin');

        $this->assertEquals('jetzt', $diffMessage);
    }

    /**
     * @throws \Exception
     */
    public function testTimezoneNowMessageWithoutSecondParam()
    {
        $dateTime = new \DateTimeImmutable('Now');
        $dateTime = $dateTime->setTimezone(new \DateTimeZone('Europe/Warsaw'));
        $diffMessage = $this->diff->timezoneDiff($dateTime, null, 'de');

        $this->assertEquals('jetzt', $diffMessage);
    }

    /**
     * @dataProvider translationOptionsDataProvider
     * @throws \Exception
     */
    public function testTimezoneDiffMessage(string $time, string $expect)
    {
        $dateTime = new \DateTimeImmutable($time);
        $diffMessage = $this->diff->timezoneDiff($dateTime);

        $this->assertEquals($expect, $diffMessage);
    }

    public static function translationOptionsDataProvider(): array
    {
        return [
            ['-1 year', '1 year ago'],
            ['-3 year', '3 years ago'],
            ['-1 month', '1 month ago'],
            ['-3 month', '3 months ago'],
            ['-1 day', '1 day ago'],
            ['-3 day', '3 days ago'],
        ];
    }
}
