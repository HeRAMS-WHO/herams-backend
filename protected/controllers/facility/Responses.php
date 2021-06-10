<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\FacilityResponse;
use prime\models\ar\Permission;
use prime\models\ar\read\Facility;
use prime\repositories\FacilityRepository;
use prime\repositories\ResponseRepository;
use prime\values\FacilityId;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class Responses extends Action
{

    public function run(
        AccessCheckInterface $check,
        FacilityRepository $facilityRepository,
        ResponseRepository $responseRepository,
        string $id
    ) {

        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForRead($facilityId);
        $check->requirePermission($facility, Permission::PERMISSION_READ);

        $dataProvider = $responseRepository->searchInFacility($facilityId);
        return $this->controller->render('responses', ['responseProvider' => $dataProvider]);
    }
}
