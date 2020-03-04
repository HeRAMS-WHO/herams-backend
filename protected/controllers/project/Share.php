<?php


namespace prime\controllers\project;


use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\models\ar\Project;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Share extends Action
{


    public function run(
        Request $request,
        User $user,
        NotificationService $notificationService,
        AuthManager $abacManager,
        int $id
    ) {
        $project = Project::findOne(['id' => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_SHARE, $project)) {
            throw new ForbiddenHttpException(\Yii::t('app', 'You are not allowed to share this project'));
        }

        try {
            $model = new ShareForm(
                $project, $abacManager, $user->identity, [
                    Permission::PERMISSION_READ,
                    Permission::PERMISSION_LIMESURVEY,
                    Permission::PERMISSION_EXPORT,
                    Permission::PERMISSION_MANAGE_WORKSPACES,
                    Permission::PERMISSION_MANAGE_DASHBOARD,
                    Permission::PERMISSION_WRITE,
                    Permission::PERMISSION_SHARE,
                    Permission::PERMISSION_SUPER_SHARE,
                ]
            );
        } catch (NoGrantablePermissions $e) {
            $notificationService->error('There are no permissions that you can share for this project');
            return $this->controller->redirect($request->getReferrer());
        }
        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->validate()) {
                $model->createRecords();
                $notificationService->success(\Yii::t('app',
                            "Project {modelName} has been shared with: {users}",
                            [
                                'modelName' => $project->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ])
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