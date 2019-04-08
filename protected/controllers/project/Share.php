<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;

class Share extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        int $id
    )
    {
        $project = Project::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new \prime\models\forms\Share($project, [], [
            'permissions' => [
                Permission::PERMISSION_READ,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_WRITE,
                Permission::PERMISSION_INSTANTIATE,
                Permission::PERMISSION_ADMIN,

            ]
        ]);
        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->createRecords()) {
                $notificationService->success(\Yii::t('app',
                            "Project {modelName} has been shared with: {users}",
                            [
                                'modelName' => $project->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                );
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('share', [
            'model' => $model,
            'project' => $project
        ]);
    }
}