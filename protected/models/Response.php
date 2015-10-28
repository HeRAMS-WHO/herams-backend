<?php


namespace prime\models;


use Befound\ActiveRecord\Behaviors\JsonBehavior;
use prime\components\ActiveRecord;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

class Response extends ActiveRecord implements ResponseInterface
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            JsonBehavior::class => [
                'class' => JsonBehavior::class,
                'jsonAttributes' => ['data']
            ]
        ]);
    }

    /**
     * @return int
     */
    public function getSurveyId()
    {
        return $this->getAttribute('survey_id');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getSubmitDate()
    {
        return $this->getAttribute('created');
    }

    /**
     * @return [] Array with all response data.
     */
    public function getData()
    {
        $this->getAttribute('data');
    }
}