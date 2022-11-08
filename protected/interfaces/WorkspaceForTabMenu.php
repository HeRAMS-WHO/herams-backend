<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;

interface WorkspaceForTabMenu extends CanCurrentUser
{
    public function id(): WorkspaceId;

    public function title(): string;

    public function projectId(): ProjectId;

    public function projectTitle(): string;

    public function getFacilityCount(): int;

    public function getPermissionSourceCount(): int;
}
