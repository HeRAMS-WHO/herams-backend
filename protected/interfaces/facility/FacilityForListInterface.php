<?php

declare(strict_types=1);

namespace prime\interfaces\facility;

use herams\common\values\FacilityId;
use prime\interfaces\CanCurrentUser;

interface FacilityForListInterface extends CanCurrentUser
{
    public function getResponseCount(): int;

    public function getId(): FacilityId;

    public function getName(): string;
}
