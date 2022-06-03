<?php

declare(strict_types=1);

namespace prime\interfaces\survey;

use prime\values\SurveyId;

interface SurveyForSurveyJsInterface
{
    public function getId(): SurveyId;

    public function getConfig(): array;
}
