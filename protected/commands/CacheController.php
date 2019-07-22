<?php


namespace prime\commands;


use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\base\ErrorException;
use yii\caching\CacheInterface;
use yii\helpers\Console;

class CacheController extends \yii\console\controllers\CacheController
{
    use ActionInjectionTrait;
    public function actionWarmup(
        LimesurveyDataProvider $limesurveyDataProvider,
        CacheInterface $cache
    ) {
        $totalFacilityCount = 0;
        $lastUpdatedProject = null;
        $lastUpdatedTimestamp = null;
        /** @var Project $project */
        foreach(Project::find()->each() as $project) {
            $this->stdout("Starting cache warmup for project {$project->title}\n", Console::FG_CYAN);
            try {
                $this->warmupProject($limesurveyDataProvider, $project, $lastUpdatedTimestamp, $totalFacilityCount, $lastUpdatedProject);
            } catch (\Throwable $t) {
                $this->stderr($t->getMessage(), Console::FG_RED);
            }
        }
        $cache->set('totalFacilityCount', $totalFacilityCount, 3600);
        $cache->set('lastUpdatedTimestamp', $lastUpdatedTimestamp->timestamp, 3600);
        $cache->set('lastUpdatedProject', $lastUpdatedProject, 3600);

    }

    protected function warmupProject(
        LimesurveyDataProvider $limesurveyDataProvider,
        Project $project,
        ?Carbon &$lastUpdatedTimestamp,
        int &$totalFacilityCount,
        ?int &$lastUpdatedProject
    ) {
        $surveyId = $project->base_survey_eid;

        /** @var Workspace $workspace */
        foreach ($project->getWorkspaces()->each() as $workspace) {
            $token = $workspace->getAttribute('token');
            $this->stdout("Starting cache warmup for workspace {$workspace->title}..\n", Console::FG_CYAN);
            $lastTime = $limesurveyDataProvider->tokenCacheTime($surveyId, $token);
            if ($lastTime === null) {
                $this->stdout("No existing cache time found\n", Console::FG_RED);
            } else {
                $diff = Carbon::createFromTimestamp($lastTime)->diffForHumans();
                $this->stdout("Last time cache was refreshed was $diff\n", Console::FG_GREEN);
            }
            $limesurveyDataProvider->refreshResponsesByToken($surveyId, $token);
            $this->stdout("OK\n", Console::FG_GREEN);

            $this->stdout("Checking responses for workspace {$workspace->title}..", Console::FG_CYAN);

            foreach($limesurveyDataProvider->getResponsesByToken($project->base_survey_eid, $workspace->getAttribute('token')) as $response) {
                $key = [
                    'id' => $response->getId(),
                    'survey_id' => $response->getSurveyId()
                ];

                $dataResponse = Response::findOne($key) ?? new Response($key);
                $dataResponse->loadData($response->getData());
                $dataResponse->last_updated = Carbon::now();
                if ($dataResponse->isNewRecord) {
                    $this->stdout($dataResponse->save() ? '+' : '-', Console::FG_RED);
                } else {
                    $dataResponse->save();
                    $this->stdout($dataResponse->save() ? '+' : '-', Console::FG_YELLOW);
                }

            }
            $this->stdout("OK\n", Console::FG_GREEN);
        }

        if (!isset($lastTime) || Carbon::createFromTimestamp($lastTime)->addMinute(20)->isPast()) {
            $this->stdout('Refreshing survey structure...', Console::FG_CYAN);
            foreach ($limesurveyDataProvider->getSurvey($project->base_survey_eid)->getGroups() as $group) {
                $this->stdout('.', Console::FG_PURPLE);
                $group->getQuestions();

            }
            $this->stdout("OK\n", Console::FG_GREEN);

        }
    }
}