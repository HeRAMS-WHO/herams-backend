<?php
declare(strict_types=1);

namespace prime\commands;

use prime\services\completeness\ResponseCompletenessFactory;
use prime\services\completeness\ResponseCompletenessInterface;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\Console;

class ResponseController extends Controller
{
    private const BATCH_SIZE = 1000;

    public $projectId;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['projectId']);
    }

    /**
     * Usage:
     *   response/recalculate-completeness                  recalculate all responses
     *   response/recalculate-completeness LBY              all responses of projects with that country
     *   response/recalculate-completeness --projectId=42   only responses of that single project
     *
     * @param string|null $country ISO3 alpha-3 code as stored in project.country
     */
    public function actionRecalculateCompleteness(?string $country = null): int
    {
        $projectId = $this->projectId !== null ? (int) $this->projectId : null;

        $db = \Yii::$app->getDb();
        $db->enableLogging = false;
        $db->enableProfiling = false;

        /** @var ResponseCompletenessInterface[] $checkers */
        $checkers = [];
        $lastId = null;
        $lastSurveyId = null;
        $total = 0;
        $updated = 0;

        do {
            $query = (new Query())
                ->select(['r.id', 'r.survey_id', 'r.data', 'r.is_complete', 'country' => 'p.country'])
                ->from(['r' => '{{%response}}'])
                ->leftJoin(['w' => '{{%workspace}}'], 'w.id = r.workspace_id')
                ->leftJoin(['p' => '{{%project}}'], 'p.id = w.tool_id')
                ->orderBy(['r.id' => SORT_ASC, 'r.survey_id' => SORT_ASC])
                ->limit(self::BATCH_SIZE);

            if ($country !== null) {
                $query->andWhere(['p.country' => $country]);
            }

            if ($projectId !== null) {
                $query->andWhere(['p.id' => $projectId]);
            }

            if ($lastId !== null) {
                $query->andWhere([
                    'or',
                    ['>', 'r.id', $lastId],
                    ['and', ['r.id' => $lastId], ['>', 'r.survey_id', $lastSurveyId]],
                ]);
            }

            $rows = $query->all($db);

            foreach ($rows as $row) {
                $lastId = $row['id'];
                $lastSurveyId = $row['survey_id'];

                $checker = $checkers[$row['country'] ?? '']
                    ??= (new ResponseCompletenessFactory($row['country']))->create();
                $data = is_string($row['data']) ? json_decode($row['data'], true) : $row['data'];
                $isComplete = $checker->isComplete(is_array($data) ? $data : []);

                if ($row['is_complete'] !== null && (bool) $row['is_complete'] === $isComplete) {
                    continue;
                }

                $db->createCommand()->update(
                    '{{%response}}',
                    ['is_complete' => $isComplete],
                    ['id' => $row['id'], 'survey_id' => $row['survey_id']]
                )->execute();
                $updated++;
            }

            $total += count($rows);
            $this->stdout("Processed $total responses, updated $updated...\n", Console::FG_CYAN);
        } while (count($rows) === self::BATCH_SIZE);

        $scope = $projectId !== null ? " (projectId: $projectId)" : ($country !== null ? " (country: $country)" : '');
        $this->stdout("Done: $total responses processed, $updated updated$scope.\n", Console::FG_GREEN);
        return ExitCode::OK;
    }
}
