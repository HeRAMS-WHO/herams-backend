<?php


namespace prime\models\ar;


use prime\components\ActiveRecord;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\validators\UniqueValidator;

class Survey extends ActiveRecord implements SurveyInterface
{
    /**
     * @return \Befound\ApplicationComponents\LimeSurvey
     */
    protected function api() {
        return app()->limesurvey;
    }
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
        foreach ($this->api()->listGroups($this->id) as $group) {
            vdd($group);
        }
        throw new \Exception('todo');
    }

    /**
     * @return string Description of the survey
     */
    public function getDescription()
    {
        return $this->api()->getLanguageProperties($this->id, ['description'])['description'];
    }

    /**
     * @return string Title of the survey
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }


    public function rules()
    {
        return [
            ['id', UniqueValidator::class]
        ];
    }

    /**
     * @return array Languages in which the survey is available
     */
    public function  getLanguages()
    {
        // TODO: Implement getLanguages() method.
    }

    /**
     * @return string The default language of the survey.
     */
    public function getDefaultLanguage()
    {
        // TODO: Implement getDefaultLanguage() method.
    }
}