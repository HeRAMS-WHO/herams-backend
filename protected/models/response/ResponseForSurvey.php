<?php

declare(strict_types=1);

namespace prime\models\response;

use prime\values\ExternalResponseId;
use prime\values\ResponseId;

class ResponseForSurvey
{

    public function __construct(
        private ResponseId $id,
        private int|null $surveyId,
        private int|null $externalResponseId,
        private string $token,
    ) {
    }

    public function getId(): ResponseId
    {
        return $this->id;
    }

    public function getExternalResponseId(): ExternalResponseId
    {
        return new ExternalResponseId($this->externalResponseId, $this->surveyId, $this->token);
    }
    public function usesLimeSurvey(): bool
    {
        return isset($this->surveyId, $this->externalResponseId);
    }
}
