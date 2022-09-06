<?php

declare(strict_types=1);

namespace prime\models\surveyResponse;

use Collecthor\SurveyjsParser\ArrayDataRecord;
use prime\interfaces\surveyResponse\SurveyResponseForSurveyJsInterface;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\SurveyResponseId;

/**
 * @codeCoverageIgnore getters only
 */
class SurveyResponseForSurveyJs extends ArrayDataRecord implements SurveyResponseForSurveyJsInterface
{
    public function __construct(
        array $data,
        private SurveyId $surveyId,
        private SurveyResponseId $id,
        private ProjectId $projectId,
    ) {
        parent::__construct($data);
    }

    public function getId(): SurveyResponseId
    {
        return $this->id;
    }

    public function getSurveyId(): SurveyId
    {
        return $this->surveyId;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function jsonSerialize(): mixed
    {
        return $this->allData();
    }
}
