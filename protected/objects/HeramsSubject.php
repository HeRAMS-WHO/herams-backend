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
        return $this->response->getValueForCode($this->code);
    }

    public function isFullyAvailable(): bool
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