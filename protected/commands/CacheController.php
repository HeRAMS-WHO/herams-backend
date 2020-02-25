<?php


namespace prime\commands;


use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\helpers\Console;

class CacheController extends \yii\console\controllers\CacheController
{
    use ActionInjectionTrait;
    public function actionWarmup(
        LimesurveyDataProvider $limesurveyDataProvider
    ) {
        /** @var Project $project */
        foreach(Project::find()->each() as $project) {
            $this->stdout("Starting cache warmup for project {$project->title}\n", Console::FG_CYAN);
            try {
                $this->warmupProject($limesurveyDataProvider, $project);
            } catch (\Throwable $t) {
                $this->stderr($t->getMessage(), Console::FG_RED);
            }
        }

    }

    public function actionWarmupSurvey(LimesurveyDataProvider $limesurveyDataProvider, int $id)
    {
        $this->stdout('Refreshing survey structure...', Console::FG_CYAN);
        foreach ($limesurveyDataProvider->getSurvey($id)->getGroups() as $group) {
            $this->stdout('.', Console::FG_PURPLE);
            $group->getQuestions();
        }
        $this->stdout("OK\n", Console::FG_GREEN);
    }

    public function actionWarmupProject(
        LimesurveyDataProvider $limesurveyDataProvider,
        int $id
    ) {
        $this->warmupProject($limesurveyDataProvider, Project::findOne(['id' => $id]));
    }

    protected function warmupProject(
        LimesurveyDataProvider $limesurveyDataProvider,
        Project $project
    ) {
        $surveyId = $project->base_survey_eid;

        /** @var Workspace $workspace */
        foreach ($project->getWorkspaces()->each() as $workspace) {
            $token = $workspace->getAttribute('token');
            $this->stdout("Starting cache warmup for workspace {$workspace->title}..\n", Console::FG_CYAN);
            $this->stdout("Checking responses for workspace {$workspace->title}..", Console::FG_CYAN);
            $ids = [];
            foreach($limesurveyDataProvider->refreshResponsesByToken($project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
                $key = [
                    'id' => $response->getId(),
                    'survey_id' => $response->getSurveyId()
                ];
                /**
                 * @var Response $dataResponse
                 */
                $dataResponse = Response::findOne($key) ?? new Response($key);
                $dataResponse->loadData($response->getData(), $workspace);
                if ($dataResponse->isNewRecord) {
                    $this->stdout($dataResponse->save() ? '+' : '-', Console::FG_RED);
                } elseif (empty($dataResponse->dirtyAttributes)) {
                    $this->stdout('0', Console::FG_GREEN);
                } else {
                    $this->stdout($dataResponse->save() ? '+' : '-', Console::FG_YELLOW);
                }
                $ids[] = $response->getId();
            }
            // Remove old records
            Response::deleteAll([
                'survey_id' => $project->base_survey_eid,
                'id' => ['not', $ids],
                'workspace_id' => $workspace->id
            ]);
            $this->stdout("OK\n", Console::FG_GREEN);
        }

        $this->actionWarmupSurvey($limesurveyDataProvider, $project->base_survey_eid);
    }
}