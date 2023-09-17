<?php

declare(strict_types=1);

namespace herams\api\controllers\user;

use herams\common\domain\favorite\Favorite;
use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use herams\common\models\Workspace;
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
        $userModel = User::findOne([
            'id' => $id,
        ]);
        if (! $user->can(PermissionOld::PERMISSION_MANAGE_FAVORITES, $userModel)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isDelete) {
            $result = Favorite::deleteAll([
                'user_id' => $userModel->id,
                'target_class' => Workspace::class,
                'target_id' => $target_id,
            ]);
            $response->setStatusCode(204);
            return $response;
        } elseif ($request->isPut) {
            try {
                $favorite = new Favorite();
                $favorite->user_id = $userModel->id;
                $favorite->target_class = Workspace::class;
                $favorite->target_id = $target_id;
                if ($favorite->save()) {
                    $response->setStatusCode(204);
                    return $response;
                } else {
                    $response->setStatusCode(422);
                    return $favorite;
                };
            } catch (IntegrityException $e) {
                throw $e;
                // Silence this for idempotence
                return;
            }
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }
}
