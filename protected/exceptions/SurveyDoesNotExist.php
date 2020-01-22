<?php

namespace prime\exceptions;

class SurveyDoesNotExist extends \Exception
{
    public function __construct(int $surveyId, ?\Throwable $previous)
    {
        parent::__construct(\Yii::t('app', 'Survey {id} does not exist', ['id' => $surveyId]), $surveyId, $previous);
    }

    public static function fromClient(\Exception $e): ?self
    {
        if (preg_match('/^Error: Invalid survey ID$/', $e->getMessage())) {
            $id = $e->getTrace()[0]['args'][0];
            return new self($id, $e->getPrevious());
        }
        return null;
    }
}