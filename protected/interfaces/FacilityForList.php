<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\values\FacilityId;
use prime\values\Point;

interface FacilityForList
{
    public function id(): FacilityId;
    public function name(): string;

    public function alternativeName(): null|string;
    public function code(): null|string;

    public function coords(): null|Point;
}
