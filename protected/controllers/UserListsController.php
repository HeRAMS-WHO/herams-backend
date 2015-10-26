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
                return $this->redirect(['user-lists/read', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
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