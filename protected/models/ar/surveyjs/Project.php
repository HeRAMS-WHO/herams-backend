<?php

declare(strict_types=1);

namespace prime\models\ar\surveyjs;

use prime\models\ar\Survey;
use prime\objects\enums\ProjectType;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

/**
 * @property int $admin_survey_id
 * @property int $data_survey_id
 */
class Project extends \prime\models\ar\Project
{
    public function getSurvey(): Survey
    {
        return Survey::findOne(['id' => $this->data_survey_id]);
    }
}
