<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\models\ar\User;
use prime\models\ar\Workspace;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class Favorites extends Action
{
    public function run(
        \yii\web\User $user
    ) {
        $this->controller->layout = 'admin-screen';
        /** @var User $model */
        $model = $user->identity;

        $query = Workspace::find()->andWhere(['id' =>
            $model->getFavorites()->filterTargetClass(Workspace::class)->select('target_id')]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $this->controller->render('favorites', [
            'dataProvider' => $dataProvider
        ]);
    }
}
