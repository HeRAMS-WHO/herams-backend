<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\permissions\Permission;
use prime\models\ar\UserList;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class UserListsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate(Request $request, Session $session, User $user)
    {
        $model = new \prime\models\forms\UserList([
            'user_id' => $user->id
        ]);

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'userListCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "User list <strong>{$model->name}</strong> is created.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['/user-lists/read', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete(Request $request, Session $session, $id)
    {
        $model = UserList::loadOne($id, Permission::PERMISSION_WRITE);
        if($request->isDelete) {
            $model->delete();
            $session->setFlash(
                'userListDeleted',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => "User list <strong>{$model->name}</strong> is deleted.",
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        return $this->redirect(['/user-lists/list']);
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionRead($id)
    {
        $model = UserList::loadOne($id);
        return $this->render('read', ['model' => $model]);
    }

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = \prime\models\forms\UserList::loadOne($id, Permission::PERMISSION_WRITE);

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'userListUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "User list <strong>{$model->name}</strong> is updated.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['user-lists/read', 'id' => $model->id]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['list', 'read'],
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}