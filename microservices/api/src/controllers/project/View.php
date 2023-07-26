<?php declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\api\domain\project\UpdateProject;
use herams\common\domain\project\ProjectRepository;
use herams\common\enums\ProjectVisibility;
use herams\common\values\ProjectId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        int $id
    ): array {
        $project = $projectRepository->retrieveForUpdate(new ProjectId($id));
        $visibility = $project->visibility;
        $project->visibility = ProjectVisibility::getValueFromText($visibility);
        $projectArray = $project->toArray();
        return $projectArray;
    }
}
