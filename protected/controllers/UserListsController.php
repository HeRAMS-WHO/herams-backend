<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\permissions\Permission;
use prime\models\UserList;
use yii\helpers\ArrayHelper;

class UserListsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate()
    {
        $model = new \prime\models\forms\UserList([
            'user_id' => app()->user->id
        ]);

        if(app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
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

    public function actionDelete($id)
    {
        $model = UserList::loadOne($id, Permission::PERMISSION_WRITE);
        if(app()->request->isDelete) {
            $model->delete();
            app()->session->setFlash(
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

    public function actionUpdate($id)
    {
        $model = \prime\models\forms\UserList::loadOne($id, Permission::PERMISSION_WRITE);

        if(app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
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