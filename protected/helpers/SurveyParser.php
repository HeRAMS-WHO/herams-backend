<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\SurveyjsParser\SurveyParser as BaseParser;
use prime\helpers\surveyjs\FacilityTypeQuestionParser;
use prime\interfaces\ColorMap;

final class SurveyParser extends BaseParser
{
    private const TYPE_FACILITYTYPE = 'facilitytype';

    public function __construct(FacilityTypeQuestionParser $facilityTypeQuestionParser)
    {
        parent::__construct();
        $this->setParser(self::TYPE_FACILITYTYPE, $facilityTypeQuestionParser);
    }

    public function parseHeramsSurveyStructure(array $structure): HeramsVariableSet
    {
        $variableSet = parent::parseSurveyStructure($structure);
        // Parse additional platform specific things.
        $colorMap = $this->parseColorMap($structure);
        return new HeramsVariableSet($variableSet, $colorMap);
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
