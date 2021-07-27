<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\interfaces\AccessCheckInterface;
use prime\repositories\FacilityRepository;
use prime\repositories\ResponseRepository;
use prime\values\FacilityId;
use yii\base\Action;

class AdminResponses extends Action
{

    public function run(
        AccessCheckInterface $check,
        FacilityRepository $facilityRepository,
        ResponseRepository $responseRepository,
        string $id
    ) {

        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $dataProvider = $responseRepository->searchInFacility($facilityId);
        return $this->controller->render('responses', [
            'responseProvider' => $dataProvider,
            'facility' => $facility
        ]);
    }
}
