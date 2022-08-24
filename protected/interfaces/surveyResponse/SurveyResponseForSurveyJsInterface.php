<?php

declare(strict_types=1);

namespace prime\interfaces\surveyResponse;

use Collecthor\DataInterfaces\RecordInterface;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\SurveyResponseId;

interface SurveyResponseForSurveyJsInterface extends RecordInterface, \JsonSerializable
{
    public function getId(): SurveyResponseId;

    public function getSurveyId(): SurveyId;

    public function getProjectId(): ProjectId;
}
