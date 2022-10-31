<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        int $id
    ) {
        return $projectRepository->retrieveForUpdate(new ProjectId($id));
    }
}
