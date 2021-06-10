<?php
declare(strict_types=1);

namespace prime\models\response;

class ResponseForSurvey
{

    public function __construct(
        private string|null $limesurveyUrl
    ) {
    }


    public function usesLimeSurvey(): bool
    {
        return isset($this->limesurveyUrl);
    }

    public function getLimesurveyUrl(): string
    {
        return $this->limesurveyUrl;
    }
}
