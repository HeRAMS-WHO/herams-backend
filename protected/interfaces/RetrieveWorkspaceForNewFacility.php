<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\models\workspace\WorkspaceForNewFacility;
use prime\values\WorkspaceId;

interface RetrieveWorkspaceForNewFacility
{
    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForNewFacility;
}
