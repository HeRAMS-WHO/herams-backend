<?php


namespace prime\controllers\workspace;

use prime\components\Controller;
use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\forms\workspace\Import as ImportModel;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Request;

class Import extends Action
{

    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        AccessCheckInterface $accessCheck,
        Request $request,
        NotificationService $notificationService,
        int $project_id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne(['id' => $project_id]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_WORKSPACES);

        /** @var array $tokens */
        $samples = $limesurveyDataProvider->getTokens($project->base_survey_eid);

        try {
            $model = new ImportModel($project, $samples);
        } catch (InvalidConfigException $e) {
            $notificationService->error($e->getMessage());
            return $this->controller->redirect(['project/workspaces', 'id' => $project->id]);
        }

        if ($request->isPost) {
            if ($model->load($request->bodyParams)
                && $model->validate()
            ) {
                $result = $model->run();
                $notificationService->info(\Yii::t('app', "Created {success} workspaces, failed to create {fail} workspaces", [
                    'success' => $result->getSuccessCount(),
                    'fail' => $result->getFailCount(),
                ]));
                return $this->controller->redirect(['project/workspaces', 'id' => $project->id]);
            }
        }

        return $this->controller->render('import', [
            'model' => $model,
            'project' => $project
        ]);
    }
}
