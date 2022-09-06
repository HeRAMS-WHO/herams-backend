<?php

declare(strict_types=1);

namespace prime\interfaces\facility;

use prime\interfaces\CanCurrentUser;
use prime\objects\enums\FacilityTier;
use prime\values\FacilityId;

interface FacilityForListInterface extends CanCurrentUser
{
    public function getResponseCount(): int;

    public function getId(): FacilityId;

    public function getName(): string;
}
