<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
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