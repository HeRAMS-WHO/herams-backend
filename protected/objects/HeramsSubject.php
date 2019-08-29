<?php


namespace prime\objects;

use prime\interfaces\HeramsResponseInterface;

/**
 * Class HeramsSubject
 * This object models a Subject from the HeRAMS survey, in most cases these are services.
 * @package prime\objects
 *
 * @method ?string getType()
 */
class HeramsSubject
{
    public const FULLY_AVAILABLE = 'A1';
    public const PARTIALLY_AVAILABLE= 'A2';
    public const NOT_AVAILABLE= 'A3';
    public const NOT_PROVIDED = 'A4';

    public const LACK_STAFF = 'A1';
    public const LACK_TRAINING = 'A2';
    public const LACK_SUPPLIES = 'A3';
    public const LACK_EQUIPMENT = 'A4';
    public const LACK_FINANCES = 'A5';


    /** @var HeramsResponseInterface */
    private $response;

    private $code;

    public function getCode(): string
    {
        return $this->code;
    }

    public function __construct(
        HeramsResponseInterface $response,
        string $code
    ) {
        $this->response = $response;
        $this->code = $code;
    }

    public function getAvailability(): ?string
    {
        $result = $this->response->getValueForCode($this->code);
        if (is_array($result)) {
            var_dump($result); die();
        }
        return $result;
    }

    public function getFullyAvailable(): bool
    {
        return $this->response->getValueForCode($this->code) === self::FULLY_AVAILABLE;
    }

    public function getCauses(): array
    {
        return $this->response->getValueForCode($this->code . 'x') ?? [];
    }

    public function __call($name, $arguments)
    {
        return $this->response->{$name}(... $arguments);
    }


}