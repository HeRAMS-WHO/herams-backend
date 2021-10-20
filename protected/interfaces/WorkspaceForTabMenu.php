<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface WorkspaceForTabMenu extends CanCurrentUser
{

    public function id(): WorkspaceId;
    public function title(): string;

    public function projectId(): ProjectId;
    public function projectTitle(): string;

    public function getFacilityCount(): int;
    public function getResponseCount(): int;
    public function getPermissionSourceCount(): int;
}
