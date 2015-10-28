<?php


namespace prime\objects;


use prime\interfaces\ResponseCollectionInterface;
use prime\objects\Collection;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

/**
 * Class ResponseCollection
 * @package prime\objects
 * @method ResponseInterface get($key)
 */
class ResponseCollection extends Collection implements ResponseCollectionInterface
{
    protected $dataType = ResponseInterface::class;
}