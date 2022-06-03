<?php

declare(strict_types=1);

namespace prime\interfaces\surveyResponse;

use prime\values\SurveyResponseId;

interface SurveyResponseForSurveyJsInterface
{
    public function getData(): array;

    public function getId(): SurveyResponseId;
}
