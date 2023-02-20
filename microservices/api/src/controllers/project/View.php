<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\domain\project\UpdateProject;
use herams\common\domain\project\ProjectRepository;
use herams\common\values\ProjectId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        int $id
    ): UpdateProject {
        return $projectRepository->retrieveForUpdate(new ProjectId($id));
    }
}
