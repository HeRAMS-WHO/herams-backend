<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\SurveyjsParser\ElementParserInterface;
use Collecthor\SurveyjsParser\ParserHelpers;
use Collecthor\SurveyjsParser\Parsers\SingleChoiceQuestionParser;
use Collecthor\SurveyjsParser\SurveyConfiguration;
use herams\common\domain\facility\HSDUStateEnum;

class HSDUStateTypeQuestionParser implements ElementParserInterface
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
        $hsduMap = [];

        foreach ($questionConfig['choices'] as $choice) {
            $hsduMap[$choice['value']] = HSDUStateEnum::from($choice['tier'] ?? HSDUStateEnum::acceptUpdates->value);
        }

        foreach ($this->singleChoiceQuestionParser->parse($root, $questionConfig, $surveyConfiguration, $dataPrefix) as $variable) {
            // We want to alter the closed question only.
            if ($variable instanceof ClosedVariableInterface) {
                yield new HSDUStateVariable($variable, $hsduMap, [
                    ...$questionConfig,
                    'showInResponseList' => $questionConfig['showTierInResponseList'] ?? false,
                    'showFacilityInResponseList' => $questionConfig['showTierInFacilityResponseList'] ?? false,
                ]);
            }
            yield $variable;
        }
    }
}
