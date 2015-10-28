<?php


namespace prime\models;


use prime\components\ActiveRecord;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

class Survey extends ActiveRecord implements SurveyInterface
{

    /**
     * @return int The unique ID for this survey.
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return GroupInterface[]
     */
    public function getGroups()
    {
        throw new \Exception('todo');
    }

    /**
     * @return string Description of the survey
     */
    public function getDescription()
    {
        throw new \Exception('todo');
    }

    /**
     * @return string Title of the survey
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }


}