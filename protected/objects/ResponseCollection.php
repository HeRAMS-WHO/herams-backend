<?php


namespace prime\objects;


use prime\interfaces\ResponseCollectionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

/**
 * Class ResponseCollection
 * @package prime\objects
 * @method ResponseInterface get($key);
 * @method append(ResponseInterface $object);
 */
class ResponseCollection extends Collection implements ResponseCollectionInterface
{
    protected $dataType = ResponseInterface::class;
}