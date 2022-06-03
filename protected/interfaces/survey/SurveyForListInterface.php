<?php

declare(strict_types=1);

namespace prime\interfaces\survey;

use prime\values\SurveyId;

interface SurveyForListInterface
{
    public function getId(): SurveyId;

    public function getTitle(): string;
}
