<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\components\Controller;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use yii\base\Action;
use yii\web\User;

class Workspaces extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        WorkspaceRepository $workspaceRepository,
        User $user,
        int $id
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $projectId = new ProjectId($id);

        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        return $this->controller->asJson($workspaces);
    }
}
