<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\SurveyjsParser\SurveyParser as BaseParser;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\ColorMap;

final class SurveyParser extends BaseParser
{
    private const TYPE_FACILITY_TYPE = 'facilitytype';

    private const TYPE_FACILITY_NAME = 'facilityName';

    public function __construct(
        FacilityTypeQuestionParser $facilityTypeQuestionParser,
        LocalizableTextQuestionParser $localizableTextQuestionParser
    ) {
        parent::__construct();
        $this->setParser(self::TYPE_FACILITY_TYPE, $facilityTypeQuestionParser);
        $this->setParser('localizableprojecttext', $localizableTextQuestionParser);

        //        $this->setParser(self::TYPE_FACILITY_NAME, $facilityTypeQuestionParser);
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