<?php
declare(strict_types=1);

namespace prime\jobs;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\values\FacilityId;

final class UpdateFacilityDataJob implements JobInterface
{
    public function __construct(public readonly FacilityId $facilityId)
    {

    }

    public static function fromArray(array $config): JobInterface
    {
        return new self(new FacilityId($config['facilityId']));
    }

    public function jsonSerialize(): mixed
    {
        return [
            'facilityId' => $this->facilityId->getValue()
        ];
    }
}
