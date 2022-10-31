<?php

declare(strict_types=1);

namespace prime\models\survey;

use prime\values\SurveyId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
final class SurveyForList implements \JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id->getValue(),
            'title' => $this->title,
        ];
    }
}
