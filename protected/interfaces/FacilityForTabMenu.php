<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface FacilityForTabMenu extends CanCurrentUser
{
    public function getId(): FacilityId;
    public function getTitle(): string;

    public function getProjectId(): ProjectId;
    public function getProjectTitle(): string;

    public function getWorkspaceId(): WorkspaceId;
    public function getWorkspaceTitle(): string;

    public function getAdminResponseCount(): int;
    public function getResponseCount(): int;
}
