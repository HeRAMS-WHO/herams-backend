<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\VariableSetInterface;
use Collecthor\SurveyjsParser\SurveyParser as BaseParser;
use prime\interfaces\ColorMap;

final class SurveyParser extends BaseParser
{
    public function parseSurveyStructure(array $structure): VariableSetInterface
    {
        $variableSet = parent::parseSurveyStructure($structure);
        // Parse additional platform specific things.
        $colorMap = $this->parseColorMap($structure);

        // Todo: augment the variable set implementation to include the color map
        return $variableSet;
    }

    private function parseColorMap(array $structure): ColorMap
    {
        $dictionary = [];
        foreach ($structure['colors'] ?? [] as $pair) {
            $dictionary[$pair['value']] = $pair['color'];
        }
        return new \prime\helpers\ColorMap($dictionary);
    }
}
