<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\CanCurrentUser;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

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
        private CanCurrentUser|null $checker = null
    ) {
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function projectTitle(): string
    {
        return $this->projectTitle;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    public function workspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }

    public function workspaceTitle(): string
    {
        return $this->workspaceTitle;
    }

    public function canCurrentUser(string $permission): bool
    {
        return isset($this->checker) && $this->checker->canCurrentUser($permission);
    }
}
