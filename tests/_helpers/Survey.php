<?php

namespace prime\tests\_helpers;

use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

class Survey implements SurveyInterface
{
    /**
     * @return int The unique ID for this survey.
     */
    public function getId()
    {
        return 12345;
    }

    /**
     * @return GroupInterface[]
     */
    public function getGroups()
    {
        return [];
    }

    /**
     * @return string Description of the survey
     */
    public function getDescription()
    {
        return "desc";
    }

    /**
     * @return string Title of the survey
     */
    public function getTitle()
    {
        return "title";
    }

    /**
     * @return array Languages in which the survey is available
     */
    public function getLanguages()
    {
        return [];
    }

    /**
     * @return string The default language of the survey.
     */
    public function getDefaultLanguage()
    {
        return "nl";
    }
}
