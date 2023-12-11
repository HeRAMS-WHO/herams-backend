<?php

declare(strict_types=1);

namespace herams\api\controllers\facility;

use herams\common\domain\facility\FacilityRepository;
use herams\common\values\FacilityId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        int $id
    ) {
        return $facilityRepository->retrieveFacility(new FacilityId($id));
    }
}
