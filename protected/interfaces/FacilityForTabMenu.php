<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface FacilityForTabMenu extends CanCurrentUser
{
    public function canReceiveSituationUpdate(): bool;
    public function getAdminResponseCount(): int;
    public function getId(): FacilityId;
    public function getProjectId(): ProjectId;
    public function getProjectTitle(): string;
    public function getResponseCount(): int;
    public function getTitle(): string;
    public function getWorkspaceId(): WorkspaceId;
    public function getWorkspaceTitle(): string;
}
