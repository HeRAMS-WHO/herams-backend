<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\models\search\FacilitySearch;
use prime\repositories\FacilityRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;

class Facilities extends Action
{
    public function run(
        Request $request,
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
