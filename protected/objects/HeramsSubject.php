<?php


namespace prime\objects;

/**
 * Class HeramsSubject
 * This object models a Subject from the HeRAMS survey, in most cases these are services.
 * @package prime\objects
 *
 */
class HeramsSubject
{
    public const FULLY_AVAILABLE = 'A1';
    public const PARTIALLY_AVAILABLE= 'A2';
    public const NOT_AVAILABLE= 'A3';
    public const NOT_PROVIDED = 'A4';


    /** @var HeramsResponse */
    private $response;

    private $code;

    public function getCode(): string
    {
        return $this->code;
    }

    public function __construct(
        HeramsResponse $response,
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

    public function getType(): ?string
    {
        return $this->response->getType();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->response, $name], $arguments);
    }


}