<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\forms\Share;
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
        $model = UserList::loadOne($id, [], Permission::PERMISSION_SHARE);
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

    public function actionList(User $user)
    {
        $userListsDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $user->identity->getUserLists()
        ]);
        return $this->render('list', ['userListsDataProvider' => $userListsDataProvider]);
    }

    public function actionRead($id)
    {
        $model = UserList::loadOne($id);
        return $this->render('read', ['model' => $model]);
    }

    public function actionShare(Session $session, Request $request, $id)
    {
        $userList = UserList::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new Share($userList, [$userList->user_id]);

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->createRecords()) {
                $session->setFlash(
                    'userListShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app',
                            "UserList <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                            [
                                'modelName' => $userList->name,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $model = new Share($userList, [$userList->user_id]);
            }
        }

        return $this->render('share', [
            'model' => $model,
            'userList' => $userList
        ]);
    }

    public function actionShareDelete(Request $request, Session $session, $id)
    {
        $permission = Permission::findOne($id);
        //User must be able to share user list in order to delete a share
        $userList = UserList::loadOne($permission->target_id, [], Permission::PERMISSION_SHARE);
        $user = $permission->sourceObject;
        if($permission->delete()) {
            $session->setFlash(
                'userListShared',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => \Yii::t(
                        'app',
                        "Stopped sharing user list <strong>{modelName}</strong> with: <strong>{user}</strong>",
                        [
                            'modelName' => $userList->name,
                            'user' => $user->name
                        ]
                    ),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect(['/user-lists/share', 'id' => $userList->id]);
    }

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = \prime\models\forms\UserList::loadOne($id, [], Permission::PERMISSION_WRITE);

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
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }
}