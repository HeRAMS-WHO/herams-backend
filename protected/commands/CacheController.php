<?php


namespace prime\commands;


use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
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

            $surveyId = $project->base_survey_eid;

            /** @var Workspace $workspace */
            foreach($project->getWorkspaces()->each() as $workspace)
            {
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
            }

            $this->stdout("Checking responses for workspace {$workspace->title}..", Console::FG_CYAN);
            foreach($project->getHeramsResponses() as $heramsResponse) {
                $totalFacilityCount++;
                $this->stdout('.', Console::FG_GREEN);
                $newDate = $heramsResponse->getDate();
                if (!isset($lastUpdatedTimestamp)
                    || (isset($newDate) && $newDate->greaterThan($lastUpdatedTimestamp))
                ) {
                    $lastUpdatedTimestamp = $newDate;
                    $lastUpdatedProject = $project->id;
                }
            }
            $this->stdout("OK\n", Console::FG_GREEN);




            if (!isset($lastTime) || Carbon::createFromTimestamp($lastTime)->addMinute(20)->isPast()) {
                $this->stdout('Refreshing survey structure...', Console::FG_CYAN);
                foreach ($limesurveyDataProvider->getSurvey($project->base_survey_eid)->getGroups() as $group) {
                    $this->stdout('.', Console::FG_PURPLE);
                    $group->getQuestions();

                }
                $this->stdout("OK\n", Console::FG_GREEN);

            }
            $cache->set('totalFacilityCount', $totalFacilityCount, 3600);
            $cache->set('lastUpdatedTimestamp', $lastUpdatedTimestamp->timestamp, 3600);
            $cache->set('lastUpdatedProject', $lastUpdatedProject, 3600);
        }
        $cache->set('totalFacilityCount', $totalFacilityCount, 3600);
        $cache->set('lastUpdatedTimestamp', $lastUpdatedTimestamp->timestamp, 3600);
        $cache->set('lastUpdatedProject', $lastUpdatedProject, 3600);

    }
}