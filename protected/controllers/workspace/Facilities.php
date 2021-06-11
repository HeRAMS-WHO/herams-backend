<?php
declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\models\search\FacilitySearch;
use prime\models\search\Workspace as WorkspaceSearch;
use prime\repositories\FacilityRepository;
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
        Request $request,
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;

        $workspaceId = new WorkspaceId($id);
        $facilitySearch = new FacilitySearch();
        $facilitySearch->load($request->queryParams);
        $facilityProvider = $facilityRepository->searchInWorkspace($workspaceId, $facilitySearch);
        return $this->controller->render('facilities', [
            'facilitySearch' => $facilitySearch,
            'facilityProvider' => $facilityProvider,
        ]);
    }
}
