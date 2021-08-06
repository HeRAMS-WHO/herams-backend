<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\objects\LanguageSet;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
final class WorkspaceForNewOrUpdateFacility implements \prime\interfaces\WorkspaceForNewOrUpdateFacility
{

    public function __construct(
        private WorkspaceId $id,
        private string $title,
        private ProjectId $projectId,
        private string $projectTitle,
        private LanguageSet $languages
    ) {
        if (count($languages) === 0) {
            throw new \Exception('Languages must not be empty');
        }
    }

    public function id(): WorkspaceId
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

    public function languages(): LanguageSet
    {
        return $this->languages;
    }
}
