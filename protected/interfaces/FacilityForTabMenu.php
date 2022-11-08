<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;

interface FacilityForTabMenu extends CanCurrentUser
{
    public function canReceiveSituationUpdate(): bool;

    public function getAdminResponseCount(): int;

    public function getId(): FacilityId;

    public function getResponseCount(): int;

    public function getTitle(): string;

    public function getWorkspaceId(): WorkspaceId;

}
