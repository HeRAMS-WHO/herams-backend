<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\objects\LanguageSet;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface WorkspaceForNewOrUpdateFacility
{

    public function id(): WorkspaceId;
    public function title(): string;

    public function projectId(): ProjectId;
    public function projectTitle(): string;

    public function languages(): LanguageSet;
}
