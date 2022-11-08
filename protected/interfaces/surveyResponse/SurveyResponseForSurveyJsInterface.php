<?php

declare(strict_types=1);

namespace prime\interfaces\surveyResponse;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\SurveyResponseId;

interface SurveyResponseForSurveyJsInterface extends RecordInterface, \JsonSerializable
{
    public function getId(): SurveyResponseId;

    public function getSurveyId(): SurveyId;

    public function getProjectId(): ProjectId;
}
