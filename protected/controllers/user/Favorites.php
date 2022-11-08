<?php

declare(strict_types=1);

namespace prime\controllers\user;

use herams\common\domain\user\User;
use herams\common\models\Workspace;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Favorites extends Action
{
    public function run(
        \yii\web\User $user
    ) {
        /** @var User $model */
        $model = $user->identity;

        $query = Workspace::find()->andWhere([
            'id' =>
                        $model->getFavorites()->filterTargetClass(Workspace::class)->select('target_id'),
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->controller->render('favorites', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
