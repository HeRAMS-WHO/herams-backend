<?php


namespace prime\controllers\workspace;


use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;
use yii\web\Session;

class Share extends Action
{
    public function run(
        Session $session,
        Request $request,
        int $id
    )
    {
        $workspace = Workspace::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new ShareForm($workspace, [$workspace->owner_id], [
            'permissions' => [
                Permission::PERMISSION_READ,
                Permission::PERMISSION_WRITE,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_ADMIN,

            ]
        ]);

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->createRecords()) {
                $session->setFlash(
                    'workspaceShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app',
                            "Project <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                            [
                                'modelName' => $workspace->title,
                                'users' => implode(', ', array_map(function ($model) {
                                    return $model->name;
                                }, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $model = new ShareForm($workspace, [
                    $workspace->owner_id,
                ]);
            }
            return $this->controller->refresh();
        }


        return $this->controller->render('share', [
            'model' => $model,
            'workspace' => $workspace
        ]);
    }
}