<?php

declare(strict_types=1);

namespace prime\models\survey;

use herams\common\values\SurveyId;
use prime\interfaces\survey\SurveyForSurveyJsInterface;

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
