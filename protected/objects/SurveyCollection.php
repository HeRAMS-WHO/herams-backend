<?php


namespace prime\objects;


use prime\interfaces\SurveyCollectionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

/**
 * Class SurveyCollection
 * @method SurveyInterface get($key)
 */
class SurveyCollection extends Collection implements SurveyCollectionInterface
{
    protected $dataType = SurveyInterface::class;
}