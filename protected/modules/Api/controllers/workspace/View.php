<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        return $workspaceRepository->retrieveForUpdate(new WorkspaceId($id));
    }
}
