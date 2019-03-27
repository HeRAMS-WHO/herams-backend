<?php


namespace prime\controllers\workspace;


use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
use prime\models\forms\workspace\Import as ImportModel;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Import extends Action
{

    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        User $user,
        Request $request,
        Session $session,
        int $project_id
    ) {
        $project = Project::loadOne($project_id);
        if (!$user->can(Permission::PERMISSION_ADMIN, $project)) {
            throw new ForbiddenHttpException();
        }

        /** @var array $tokens */
        $samples = $limesurveyDataProvider->getTokens($project->base_survey_eid);

        $model = new ImportModel($project, $samples);

        if($request->isPost) {
            if($model->load($request->bodyParams)
                && $model->validate()
            ) {
                $result = $model->run();
                $session->setFlash(
                    'workspaceCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Created {success} workspaces, failed to create {fail} workspaces", [
                            'success' => $result->getSuccessCount(),
                            'fail' => $result->getFailCount(),
                        ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->controller->redirect(['project/workspaces', 'id' => $project->id]);
            }
        }

        return $this->controller->render('import', [
            'model' => $model,
            'project' => $project
        ]);
    }


}