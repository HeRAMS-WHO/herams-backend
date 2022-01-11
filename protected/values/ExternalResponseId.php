<?php

declare(strict_types=1);

namespace prime\values;

class ExternalResponseId
{
    public function __construct(private int $responseId, private int $surveyId, private string $token)
    {
    }

    public function getResponseId(): int
    {
        return $this->responseId;
    }

    public function getSurveyId(): int
    {
        return $this->surveyId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getLimesurveyUrl(string $language): string
    {
        return "https://ls.herams.org/{$this->surveyId}?ResponsePicker={$this->responseId}&token={$this->token}&lang={$language}&newtest=Y";
    }
}
