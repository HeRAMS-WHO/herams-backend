<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\SurveyjsParser\ElementParserInterface;
use Collecthor\SurveyjsParser\ParserHelpers;
use Collecthor\SurveyjsParser\Parsers\SingleChoiceQuestionParser;
use Collecthor\SurveyjsParser\SurveyConfiguration;
use prime\objects\enums\FacilityType;

class FacilityTypeQuestionParser implements ElementParserInterface
{
    use ParserHelpers;

    public function __construct(
        private SingleChoiceQuestionParser $singleChoiceQuestionParser
    ) {
    }

    public function parse(
        ElementParserInterface $root,
        array $questionConfig,
        SurveyConfiguration $surveyConfiguration,
        array $dataPrefix = []
    ): iterable {
        $tierMap = [];
        foreach ($questionConfig['choices'] as $choice) {
            $tierMap[$choice['value']] = FacilityType::from($choice['tier']);
        }

        foreach ($this->singleChoiceQuestionParser->parse($root, $questionConfig, $surveyConfiguration, $dataPrefix) as $variable) {
            // We want to alter the closed question only.
            if ($variable instanceof ClosedVariableInterface) {
                yield new FacilityTypeVariable($variable, $tierMap);
            } else {
                yield $variable;
            }
        }
    }
}
