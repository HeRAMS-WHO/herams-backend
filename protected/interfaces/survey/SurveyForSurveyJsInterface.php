<?php

declare(strict_types=1);

namespace prime\interfaces\survey;

use herams\common\values\SurveyId;

interface SurveyForSurveyJsInterface
{
    public function getId(): SurveyId;

    public function getConfig(): array;
}
