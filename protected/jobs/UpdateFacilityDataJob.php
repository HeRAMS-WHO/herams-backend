<?php

declare(strict_types=1);

namespace prime\jobs;

use herams\common\values\FacilityId;
use JCIT\jobqueue\interfaces\JobInterface;

final class UpdateFacilityDataJob implements JobInterface
{
    public readonly FacilityId $facilityId;

    public function __construct(FacilityId|string|int $facilityId)
    {
        $this->facilityId = $facilityId instanceof FacilityId ? $facilityId : new FacilityId($facilityId);
    }

    public static function fromArray(array $config): JobInterface
    {
        return new self($config['facilityId']);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'facilityId' => $this->facilityId->getValue(),
        ];
    }
}
