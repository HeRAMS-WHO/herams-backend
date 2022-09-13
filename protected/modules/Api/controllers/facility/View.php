<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\facility;

use prime\repositories\FacilityRepository;
use prime\values\FacilityId;
use yii\base\Action;

final class View extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        int $id
    ) {
        return $facilityRepository->retrieveForUpdate(new FacilityId($id));
    }
}
