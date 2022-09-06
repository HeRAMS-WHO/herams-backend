<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\SurveyjsParser\ElementParserInterface;
use Collecthor\SurveyjsParser\ParserHelpers;
use Collecthor\SurveyjsParser\SurveyConfiguration;
use Collecthor\SurveyjsParser\Variables\NumericVariable;
use Collecthor\SurveyjsParser\Variables\OpenTextVariable;

class MultipleTextQuestionParser implements ElementParserInterface
{
    use ParserHelpers;

    public function parse(ElementParserInterface $root, array $questionConfig, SurveyConfiguration $surveyConfiguration, array $dataPrefix = []): iterable
    {
        /** @phpstan-var non-empty-list<string> $dataPath */
        $dataPath = [...$dataPrefix, $this->extractValueName($questionConfig)];

        $name = implode('.', [...$dataPrefix, $questionConfig['name']]);
        $titles = $this->extractTitles($questionConfig, $surveyConfiguration);

        \Yii::debug($questionConfig);
        return [];
        if (($questionConfig['inputType'] ?? 'text') === 'number') {
            yield new NumericVariable($name, $titles, $dataPath);
        } else {
            yield new OpenTextVariable($name, $titles, $dataPath);
        }

        yield from $this->parseCommentField($questionConfig, $surveyConfiguration, $dataPrefix);
    }
}
