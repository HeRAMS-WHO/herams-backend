<?php

declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\CanCurrentUser;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
final class FacilityForTabMenu implements \prime\interfaces\FacilityForTabMenu
{
    public function __construct(
        private FacilityId $id,
        private string $title,
        private ProjectId $projectId,
        private string $projectTitle,
        private WorkspaceId $workspaceId,
        private string $workspaceTitle,
        private int $responseCount,
        private int $adminResponseCount,
        private CanCurrentUser|null $checker = null
    ) {
    }

    public function canCurrentUser(string $permission): bool
    {
        return isset($this->checker) && $this->checker->canCurrentUser($permission);
    }

    public function getAdminResponseCount(): int
    {
        return $this->adminResponseCount;
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getProjectTitle(): string
    {
        return $this->projectTitle;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }

    public function getWorkspaceTitle(): string
    {
        return $this->workspaceTitle;
    }
}
