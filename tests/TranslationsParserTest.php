<?php

namespace Appliora\Bundle\TimezoneBundle\Tests;

use Appliora\Bundle\TimezoneBundle\Translators;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Translator;

class TranslationsParserTest extends TestCase
{
    private Translators $translationsParser;

    protected function setUp(): void
    {
        $translator = new Translator('');

        $this->translationsParser = new Translators($translator);
    }

    public function testEnglishTranslateWhenNotFindYamlFile()
    {
        $trans = $this->translationsParser->translate('None/None', ['time', 'ago', 'year'], 1);

        $this->assertSame('1 year ago', $trans);
    }

    /**
     * @dataProvider translationOptionsDataProvider
     */
    public function testTranslationsForDiffTimezone(string $timezone, array $key, int $count, string $expect)
    {
        $trans = $this->translationsParser->translate($timezone, $key, $count);

        $this->assertSame($expect, $trans);
    }

    public static function translationOptionsDataProvider(): array
    {
        return [
            ['Europe/Warsaw*', ['time', 'ago', 'year'], 1, 'rok temu'],
            ['Europe/Warsaw*', ['time', 'ago', 'year'], 3, '3 lata temu'],
            ['Europe/Warsaw*', ['time', 'ago', 'day'], 6, '6 dni temu'],
            ['', ['time', 'ago', 'year'], 1, '1 year ago'],
            ['', ['time', 'ago', 'month'], 3, '3 months ago'],
            ['', ['time', 'ago', 'year'], 6, '6 years ago'],
            ['UTC', ['time', 'ago', 'year'], 1, '1 year ago'],
            ['UTC', ['time', 'ago', 'year'], 3, '3 years ago'],
            ['UTC', ['time', 'ago', 'minute'], 6, '6 minutes ago'],
            ['Asia/Dubai*', ['time', 'ago', 'year'], 1, 'منذ سنة'],
            ['Asia/Dubai*', ['time', 'ago', 'year'], 6, 'منذ 6 سنوات'],
        ];
    }
}
