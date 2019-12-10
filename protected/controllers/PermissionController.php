<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class PermissionController extends Controller
{
    public function actionDelete(
        User $user,
        int $id,
        string $redirect
    ) {
        $permission = Permission::findOne(['id' => $id]);
        if (!isset($permission)) {
            throw new NotFoundHttpException();
        }

        /** @var ActiveRecord $target */
        $target = $permission->targetObject;
        if (!isset($target)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_UNSHARE, $target)) {
            throw new ForbiddenHttpException();
        }

        $permission->delete();
        return $this->redirect($redirect);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}