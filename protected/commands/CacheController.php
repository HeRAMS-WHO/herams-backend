<?php


namespace prime\commands;


use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
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

            $surveyId = $project->base_survey_eid;
            $lastTime = $limesurveyDataProvider->responseCacheTime($surveyId);
            if ($lastTime === null) {
                $this->stdout("No existing cache time found\n", Console::FG_RED);
            } else {
                $diff = Carbon::createFromTimestamp($lastTime)->diffForHumans();
                $this->stdout("Last time cache was refreshed was $diff\n", Console::FG_GREEN);
            }

            if (!isset($lastTime) || Carbon::createFromTimestamp($lastTime)->addMinute(20)->isPast()) {
                $start = Carbon::now();
                $limesurveyDataProvider->refreshResponses($surveyId);
                $this->stdout("Cache refreshed({$start->diffForHumans(null, true)})\n", Console::FG_GREEN);

                $this->stdout('Refreshing survey structure...', Console::FG_CYAN);
                foreach ($limesurveyDataProvider->getSurvey($project->base_survey_eid)->getGroups() as $group) {
                    $this->stdout('.', Console::FG_PURPLE);
                    $group->getQuestions();

                }
                $this->stdout("OK\n", Console::FG_GREEN);

            }

        }
    }
}