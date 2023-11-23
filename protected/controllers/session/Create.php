<?php

declare(strict_types=1);

namespace prime\controllers\session;

use herams\common\helpers\ModelHydrator;
use prime\models\forms\LoginForm;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{
    public function run(
        User $user,
        Request $request,
        CacheInterface $cache,
        ModelHydrator $modelHydrator,
    ) {
        if (! $user->getIsGuest()) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();

        if ($request->isPost) {
            $modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->login()) {
                return $this->controller->goBack();
            }
        }

        return $this->controller->render('create', [
            'model' => $model,
        ]);
    }
}
