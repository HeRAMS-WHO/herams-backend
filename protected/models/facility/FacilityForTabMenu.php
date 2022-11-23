<?php

declare(strict_types=1);

namespace prime\models\facility;

use herams\common\values\FacilityId;
use herams\common\values\WorkspaceId;
use prime\interfaces\CanCurrentUser;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
final class FacilityForTabMenu implements \prime\interfaces\FacilityForTabMenu
{
    public function __construct(
        private FacilityId $id,
        private string $title,
        private WorkspaceId $workspaceId,
        private int $responseCount,
        private int $adminResponseCount,
        private bool $canReceiveSituationUpdate,
        private CanCurrentUser|null $checker = null,
    ) {
    }

    public function canCurrentUser(string $permission): bool
    {
        return isset($this->checker) && $this->checker->canCurrentUser($permission);
    }

    public function canReceiveSituationUpdate(): bool
    {
        return $this->canReceiveSituationUpdate;
    }

    public function getAdminResponseCount(): int
    {
        return $this->adminResponseCount;
    }

    public function getId(): FacilityId
    {
        return $this->id;
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
}
