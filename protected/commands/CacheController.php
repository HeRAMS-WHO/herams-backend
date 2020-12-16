<?php


namespace prime\commands;

use prime\components\LimesurveyDataProvider;
use prime\helpers\LimesurveyDataLoader;
use prime\models\ar\Project;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use yii\helpers\Console;

class CacheController extends \yii\console\controllers\CacheController
{
    public function actionResync(LimesurveyDataProvider $limesurveyDataProvider)
    {
        /** @var Project $project */
        foreach (Project::find()->each() as $project) {
            $this->stdout("Removing all responses for project {$project->title}\n", Console::FG_CYAN);
            Response::deleteAll([
                'workspace_id' => $project->getWorkspaces()->select('id')
            ]);
            $this->stdout("Starting cache warmup for project {$project->title}\n", Console::FG_CYAN);
            try {
                $this->warmupProject($limesurveyDataProvider, $project);
            } catch (\Throwable $t) {
                $this->stderr($t->getMessage(), Console::FG_RED);
            }
        }
    }

    public function actionWarmup(LimesurveyDataProvider $limesurveyDataProvider)
    {
        /** @var Project $project */
        foreach (Project::find()->each() as $project) {
            $this->stdout("Starting cache warmup for project {$project->title}\n", Console::FG_CYAN);
            try {
                $this->warmupProject($limesurveyDataProvider, $project);
            } catch (\Throwable $t) {
                $this->stderr($t->getMessage(), Console::FG_RED);
            }
        }
    }

    public function actionWarmupSurveys(LimesurveyDataProvider $limesurveyDataProvider): void
    {
        foreach ($limesurveyDataProvider->listSurveys() as $survey) {
            $this->actionWarmupSurvey($limesurveyDataProvider, (int) $survey['sid']);
        }
    }

    public function actionWarmupSurvey(LimesurveyDataProvider $limesurveyDataProvider, int $id)
    {
        $this->stdout("Refreshing survey structure ($id)...", Console::FG_CYAN);
        foreach ($limesurveyDataProvider->getSurvey($id)->getGroups() as $group) {
            $this->stdout('.', Console::FG_PURPLE);
            $group->getQuestions();
        }
        $this->stdout("OK\n", Console::FG_GREEN);
    }

    public function actionWarmupProject(
        LimesurveyDataProvider $limesurveyDataProvider,
        int $id,
        int $afterWorkspace = 0
    ) {
        $this->warmupProject($limesurveyDataProvider, Project::findOne(['id' => $id]), $afterWorkspace);
    }

    public function actionWarmupWorkspace(
        LimesurveyDataProvider $limesurveyDataProvider,
        int $id
    ) {
        $this->warmupWorkspace(Workspace::findOne(['id' => $id]), $limesurveyDataProvider);
    }

    protected function warmupProject(
        LimesurveyDataProvider $limesurveyDataProvider,
        Project $project,
        int $afterWorkspace = 0
    ) {
        /** @var Workspace $workspace */
        foreach ($project->getWorkspaces()
                     ->orderBy('id')
                     ->andWhere(['>', 'id', $afterWorkspace])
                     ->each() as $workspace) {
            $this->warmupWorkspace($workspace, $limesurveyDataProvider);
        }

        $this->actionWarmupSurvey($limesurveyDataProvider, $project->base_survey_eid);
    }

    private function warmupWorkspace(Workspace $workspace, LimesurveyDataProvider $limesurveyDataProvider)
    {
        $loader = new LimesurveyDataLoader();
        $token = $workspace->getAttribute('token');
        $this->stdout("Starting cache warmup for workspace [{$workspace->id}] {$workspace->title}..\n", Console::FG_CYAN);
        $this->stdout("Checking responses for workspace {$workspace->title}..", Console::FG_CYAN);
        $ids = [];
        foreach ($limesurveyDataProvider->refreshResponsesByToken($workspace->project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
            $key = [
                'id' => $response->getId(),
                'survey_id' => $response->getSurveyId()
            ];
            /**
             * @var Response $responseModel
             */
            $responseModel = Response::findOne($key) ?? new Response($key);
            $loader->loadData($response->getData(), $workspace, $responseModel);
            if ($responseModel->isNewRecord) {
                $this->stdout($responseModel->save() ? '+' : '-', Console::FG_RED);
            } elseif (empty($responseModel->dirtyAttributes)) {
                $this->stdout('0', Console::FG_GREEN);
            } else {
                $this->stdout($responseModel->save() ? '+' : '-', Console::FG_YELLOW);
            }
            $ids[] = $response->getId();
        }
        // Remove old records
        Response::deleteAll([
            'and',
            [
                'survey_id' => $workspace->project->base_survey_eid,
                'workspace_id' => $workspace->id,
            ],
            ['not in', 'id', $ids],

        ]);

        $this->stdout("OK\n", Console::FG_GREEN);
    }
}
