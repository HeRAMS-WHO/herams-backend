<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface FacilityForTabMenu extends CanCurrentUser
{

    public function getId(): FacilityId;
    public function title(): string;

    public function projectId(): ProjectId;
    public function projectTitle(): string;

    public function workspaceId(): WorkspaceId;
    public function workspaceTitle(): string;

    public function getResponseCount(): int;
}
