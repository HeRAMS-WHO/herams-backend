<?php

namespace prime\helpers;

use prime\traits\SurveyHelper as SurveyHelperTrait;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

class SurveyHelper
{
    use SurveyHelperTrait {
        SurveyHelperTrait::findQuestionByCode as public;
        SurveyHelperTrait::getAnswers as public;
    }


    public function __construct(SurveyInterface $survey)
    {
        $this->survey = $survey;
    }
}
