<?php

declare(strict_types=1);

namespace prime\models\survey;

use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\values\SurveyId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class SurveyForSurveyJs implements SurveyForSurveyJsInterface
{
    public function __construct(
        private SurveyId $id,
        private array $config,
    ) {
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getId(): SurveyId
    {
        return $this->id;
    }
}
