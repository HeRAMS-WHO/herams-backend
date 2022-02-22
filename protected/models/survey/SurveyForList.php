<?php

declare(strict_types=1);

namespace prime\models\survey;

use prime\interfaces\survey\SurveyForListInterface;
use prime\values\SurveyId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class SurveyForList implements SurveyForListInterface
{
    public function __construct(
        private SurveyId $id,
        private string $title,
    ) {
    }

    public function getId(): SurveyId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
