<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\SurveyjsParser\ElementParserInterface;
use Collecthor\SurveyjsParser\ParserHelpers;
use Collecthor\SurveyjsParser\Parsers\SingleChoiceQuestionParser;
use Collecthor\SurveyjsParser\SurveyConfiguration;
use herams\common\domain\facility\FacilityTier;

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
            $tierMap[$choice['value']] = FacilityTier::from($choice['tier'] ?? FacilityTier::Primary->value);
        }

        foreach ($this->singleChoiceQuestionParser->parse($root, $questionConfig, $surveyConfiguration, $dataPrefix) as $variable) {
            // We want to alter the closed question only.
            if ($variable instanceof ClosedVariableInterface) {
                yield new FacilityTierVariable($variable, $tierMap, [
                    ...$questionConfig,
                    'showInResponseList' => $questionConfig['showTierInResponseList'] ?? false,
                    'showFacilityInResponseList' => $questionConfig['showTierInFacilityResponseList'] ?? false,
                ]);
            }
            yield $variable;
        }
    }
}
