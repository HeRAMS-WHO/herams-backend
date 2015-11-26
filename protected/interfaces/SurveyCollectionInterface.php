<?php

namespace prime\interfaces;

use SamIT\LimeSurvey\Interfaces\SurveyInterface;

/**
 * Interface ResponseCollectionInterface
 * @package prime\interfaces
 * @method SurveyInterface get($key)
 */
interface SurveyCollectionInterface extends CollectionInterface, \JsonSerializable
{

}