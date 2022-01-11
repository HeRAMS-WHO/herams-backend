<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\FacilityResponse;
use prime\models\ar\Permission;
use prime\models\ar\read\Facility;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Responses extends Action
{

    public function run(
        AccessCheckInterface $check,
        string $id
    ) {
        $facility = Facility::find()->withIdentity($id)->one();
        $check->requirePermission($facility, Permission::PERMISSION_READ);

        $query = FacilityResponse::find()->andWhere(['facility_id' => $facility->id]);
        return $this->controller->render('responses', ['responseProvider' => new ActiveDataProvider(['query' => $query])]);
    }
}
