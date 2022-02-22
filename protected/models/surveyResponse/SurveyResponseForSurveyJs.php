<?php

declare(strict_types=1);

namespace prime\models\surveyResponse;

use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\values\SurveyResponseId;

/**
 * @codeCoverageIgnore getters only
 */
class SurveyResponseForSurveyJs implements SurveyResponseForSurveyJsInterface
{
    public function __construct(
        private array $data,
        private SurveyResponseId $id,
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getId(): SurveyResponseId
    {
        return $this->id;
    }
}
