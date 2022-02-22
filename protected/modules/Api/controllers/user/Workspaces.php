<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\user;

use prime\models\ar\Favorite;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\models\ar\WorkspaceForLimesurvey;
use yii\base\Action;
use yii\db\IntegrityException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;

class Workspaces extends Action
{
    public function run(
        \yii\web\User $user,
        Request $request,
        \yii\web\Response $response,
        int $id,
        int $target_id
    ) {
        $userModel = User::findOne(['id' => $id]);
        if (!$user->can(Permission::PERMISSION_MANAGE_FAVORITES, $userModel)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isDelete) {
            $result = Favorite::deleteAll([
                'user_id' => $userModel->id,
                'target_class' => WorkspaceForLimesurvey::class,
                'target_id' => $target_id
            ]);
            return;
        } elseif ($request->isPut) {
            try {
                $favorite = new Favorite();
                $favorite->user_id = $userModel->id;
                $favorite->target_class = WorkspaceForLimesurvey::class;
                $favorite->target_id = $target_id;
                if ($favorite->save()) {
                    $response->setStatusCode(201);
                    return;
                } else {
                    $response->setStatusCode(422);
                    return $favorite;
                };
            } catch (IntegrityException $e) {
                // Silence this for idempotence
                return;
            }
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }
}
