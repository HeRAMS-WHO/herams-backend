<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\components\Controller;
use prime\models\ar\Favorite;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\db\IntegrityException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

class UserController extends Controller
{

    public function actionWorkspaces(
        \yii\web\User $user,
        Request $request,
        int $id,
        int $target_id
    ) {
        $userModel = User::findOne(['id' => $id]);
        if (!$user->can(Permission::PERMISSION_WRITE, $userModel)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isDelete) {
            Favorite::deleteAll([
                'user_id' => $userModel->id,
                'target_class' => Workspace::class,
                'target_id' => $target_id
            ]);
            return;
        } elseif ($request->isPut) {
            try {
                $favorite = new Favorite();
                $favorite->user_id = $userModel->id;
                $favorite->target_class = Workspace::class;
                $favorite->target_id = $target_id;
                return $favorite->save();
            } catch (IntegrityException $e) {
                // Silence this for idempotence
                return;
            }
        }

        throw new BadRequestHttpException();
    }
}
