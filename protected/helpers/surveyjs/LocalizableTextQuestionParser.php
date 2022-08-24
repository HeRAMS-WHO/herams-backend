<?php
declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\SurveyjsParser\ElementParserInterface;
use Collecthor\SurveyjsParser\ParserHelpers;
use Collecthor\SurveyjsParser\SurveyConfiguration;

class LocalizableTextQuestionParser implements ElementParserInterface
{
    use ParserHelpers;

    public function parse(ElementParserInterface $root, array $questionConfig, SurveyConfiguration $surveyConfiguration, array $dataPrefix = []): iterable
    {
        /** @phpstan-var non-empty-list<string> $dataPath */
        $dataPath = [...$dataPrefix, $this->extractValueName($questionConfig)];

        $name = implode('.', [...$dataPrefix, $questionConfig['name']]);
        $titles = $this->extractTitles($questionConfig, $surveyConfiguration);


        yield new LocalizableTextVariable($name, $titles, $dataPath);
    }
}
