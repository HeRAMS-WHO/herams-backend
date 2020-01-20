<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Grant;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class PermissionController extends Controller
{
    public function actionDelete(
        User $user,
        AuthManager $abacManager,
        int $id,
        string $redirect
    ) {
        $permission = Permission::findOne(['id' => $id]);
        if (!isset($permission)) {
            throw new NotFoundHttpException();
        }

        $grant = $permission->getGrant();
        if (!$user->can(Permission::PERMISSION_DELETE, $grant)) {
            throw new ForbiddenHttpException();
        }

        $abacManager->getRepository()->revoke($grant);
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