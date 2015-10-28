<?php

namespace prime\interfaces;

use SamIT\LimeSurvey\Interfaces\ResponseInterface;

/**
 * Interface ResponseCollectionInterface
 * @package prime\interfaces
 * @method ResponseInterface get($key)
 */
interface ResponseCollectionInterface extends CollectionInterface, \JsonSerializable
{

}