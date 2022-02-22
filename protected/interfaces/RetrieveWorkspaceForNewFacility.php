<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\values\WorkspaceId;

interface RetrieveWorkspaceForNewFacility
{
    public function retrieveForNewFacility(WorkspaceId $id): WorkspaceForCreateOrUpdateFacility;
}
