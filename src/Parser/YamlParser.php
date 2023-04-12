<?php

namespace Appliora\Bundle\TimezoneBundle\Parser;

use Symfony\Component\Yaml\Yaml;

readonly class YamlParser
{
    protected function getYamlParseFile(string $fileName): array
    {
        try {
            return Yaml::parseFile($this->findYamlFile($fileName));
        } catch (\Throwable $e) {
            return Yaml::parseFile(__DIR__ . '/../Translations/UTC.yml');
        }
    }

    protected function getParam(array $translations, array $keys): string
    {
        foreach ($keys as $key) {
            $translations = $translations[$key];
        }

        return $translations;
    }

    /**
     * @throws \Exception
     */
    private function findYamlFile(string $fileName)
    {
        if (empty($yml = glob(__DIR__ . '/../Translations/' . $fileName . '.yml', GLOB_BRACE))) {
            throw new \Exception();
        }

        return $yml[0];
    }
}