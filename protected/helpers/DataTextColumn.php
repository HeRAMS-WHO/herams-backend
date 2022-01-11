<?php
declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\HeramsResponseInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;

class DataTextColumn extends RawDataColumn
{
    private $map = [];

    public function __construct(QuestionInterface ...$questionPath)
    {
        parent::__construct(...$questionPath);
        /** @var QuestionInterface $question */
        $question = array_pop($questionPath);
        foreach ($question->getAnswers() ?? [] as $answer) {
            $this->map[$answer->getCode()] = $answer->getText();
        }
    }

    public function getValue(HeramsResponseInterface $response): ?string
    {
        $code = parent::getValue($response);
        return $this->map[$code] ?? $code;
    }
}
