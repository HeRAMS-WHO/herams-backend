<?php
declare(strict_types=1);

namespace prime\interfaces;


use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface WorkspaceForNewFacility
{

    public function id(): WorkspaceId;
    public function title(): string;

    public function projectId(): ProjectId;
    public function projectTitle(): string;

}
