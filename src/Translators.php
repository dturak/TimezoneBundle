<?php

namespace Appliora\Bundle\TimezoneBundle;

use Appliora\Bundle\TimezoneBundle\Parser\YamlParser;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class Translators  extends YamlParser
{
    public function __construct(private TranslatorInterface $translator) {}

    public function translate(?string $fileName, array $keys, int $countTime = null): string
    {
        $transFile = $this->getYamlParseFile($fileName);
        $trans = $this->getParam($transFile, $keys);

        return $this->translator->trans($trans, ['%count%' => $countTime], 'time', $transFile['lang']);
    }
}