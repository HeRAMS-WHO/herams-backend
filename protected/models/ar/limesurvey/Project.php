<?php

declare(strict_types=1);

namespace prime\models\ar\limesurvey;

use prime\components\LimesurveyDataProvider;
use prime\models\ar\Survey;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

/**
 * @property int $base_survey_eid
 */
class Project extends \prime\models\ar\Project
{
    public function getSurvey(): SurveyInterface|Survey
    {
        return $this->limesurveyDataProvider()->getSurvey($this->base_survey_eid);
    }

    private function limesurveyDataProvider(): LimesurveyDataProvider
    {
        return app()->limesurveyDataProvider;
    }
}
