<?php


namespace prime\controllers\workspace;


use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\models\ar\Project;
use prime\models\forms\workspace\Import as ImportModel;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Import extends Action
{

    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        User $user,
        Request $request,
        NotificationService $notificationService,
        int $project_id
    ) {
        $project = Project::loadOne($project_id, [], Permission::PERMISSION_WRITE);

        /** @var array $tokens */
        $samples = $limesurveyDataProvider->getTokens($project->base_survey_eid);

        $model = new ImportModel($project, $samples);

        if($request->isPost) {
            if($model->load($request->bodyParams)
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