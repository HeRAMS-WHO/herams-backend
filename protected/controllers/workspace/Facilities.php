<?php
declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\models\search\FacilitySearch;
use prime\models\search\Workspace as WorkspaceSearch;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Facilities extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        User $user,
        AccessCheckInterface $accessCheck,
        Request $request,
        WorkspaceRepository $workspaceRepository,
        int $id
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspace = $workspaceRepository->retrieveForRead(new WorkspaceId($id));

        $facilitySearch = new FacilitySearch(new WorkspaceId($id));
        $facilityProvider = $facilitySearch->search($request->queryParams);
        return $this->controller->render('facilities', [
            'facilitySearch' => $facilitySearch,
            'facilityProvider' => $facilityProvider,
            'workspace' => $workspace,
        ]);
    }
}
