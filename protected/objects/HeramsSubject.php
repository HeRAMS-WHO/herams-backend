<?php

namespace prime\objects;

use herams\common\interfaces\HeramsResponseInterface;

/**
 * Class HeramsSubject
 * This object models a Subject from the HeRAMS survey, in most cases these are services.
 * @package prime\objects
 *
 * @method ?string getType()
 */
class HeramsSubject
{
    public const UNKNOWN_VALUE = '_UNKNOWN';

    public const FULLY_AVAILABLE = 'A1';

    public const PARTIALLY_AVAILABLE = 'A2';

    public const NOT_AVAILABLE = 'A3';

    public const NOT_PROVIDED = 'A4';

    public const LACK_STAFF = 'A1';

    public const LACK_TRAINING = 'A2';

    public const LACK_SUPPLIES = 'A3';

    public const LACK_EQUIPMENT = 'A4';

    public const LACK_FINANCES = 'A5';

    /**
     * @var HeramsResponseInterface
     */
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

    public function getFullyAvailable(): bool
    {
        return $this->response->getValueForCode($this->code) === self::FULLY_AVAILABLE;
    }

    public function getCauses(): array
    {
        return $this->response->getValueForCode($this->code . 'x') ?? [];
    }

    public function getValueForCode(string $code)
    {
        switch ($code) {
            case 'availability':
                return $this->getAvailability();
            case 'fullyAvailable':
                return $this->getFullyAvailable();
            default:
                return $this->response->getValueForCode($code);
        }
    }

    public function __call($name, $arguments)
    {
        return $this->response->{$name}(...$arguments);
    }
}
