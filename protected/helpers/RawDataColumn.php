<?php
declare(strict_types=1);

namespace prime\helpers;


use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use function foo\func;
use function iter\map;
use function iter\toArray;

class RawDataColumn implements ColumnDefinition
{
    private $headerText;

    private $path;

    public function __construct(QuestionInterface ...$questionPath)
    {
        $this->path = toArray(map(function(QuestionInterface $question) { return $question->getTitle(); }, $questionPath));
        $this->headerText = implode(' ', toArray(map(function(QuestionInterface $question) { return $question->getText(); }, $questionPath)));
    }

    public function getHeaderText(): string
    {
        return $this->headerText;
    }

    public function getHeaderCode(): string
    {
        return implode('_', $this->path);
    }

    public function getValue(HeramsResponseInterface $response): ?string
    {
        $data = $response->getRawData();
        /** @var QuestionInterface $question */
        foreach($this->path as $code) {
            $data = $data[$code] ?? null;
        }
        if (is_array($data)) {
            return "Bad data, got array: " . implode(', ', $data);
        }
        return $data;
    }
}