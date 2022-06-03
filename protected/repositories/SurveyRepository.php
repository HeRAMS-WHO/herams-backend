<?php

declare(strict_types=1);

namespace prime\repositories;

use Collecthor\DataInterfaces\VariableSetInterface;
use Collecthor\SurveyjsParser\VariableSet;
use prime\components\HydratedActiveDataProvider;
use prime\helpers\HeramsVariableSet;
use prime\helpers\ModelHydrator;
use prime\helpers\SurveyParser;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\SurveyRepositoryInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Survey as SurveyForRead;
use prime\models\ar\read\Survey as SurveyRead;
use prime\models\ar\Survey;
use prime\models\ar\Workspace;
use prime\models\forms\survey\CreateForm;
use prime\models\forms\survey\UpdateForm;
use prime\models\search\SurveySearch;
use prime\models\survey\SurveyForList;
use prime\models\survey\SurveyForSurveyJs;
use prime\values\SurveyId;
use prime\values\WorkspaceId;
use yii\base\InvalidArgumentException;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;

use function iter\chain;
use function iter\map;
use function iter\toArray;

/**
 *
 */
final class SurveyRepository implements SurveyRepositoryInterface
{
    public function __construct(
        private SurveyParser $surveyParser,
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
    ) {
    }

    public function create(CreateForm $model): SurveyId
    {
        $record = new Survey();
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_CREATE);
        $this->hydrator->hydrateActiveRecord($model, $record);
        if (!$record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }

    public function retrieveAdminSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface
    {
        $workspace = Workspace::findOne(['id' => $workspaceId]);
        $surveyId = new SurveyId($workspace->project->admin_survey_id);
        return $this->retrieveForSurveyJs($surveyId);
    }

    public function retrieveDataSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface
    {
        $workspace = Workspace::findOne(['id' => $workspaceId]);
        $surveyId = new SurveyId($workspace->project->data_survey_id);
        return $this->retrieveForSurveyJs($surveyId);
    }

    public function retrieveForSurveyJs(SurveyId $id): SurveyForSurveyJsInterface
    {
        $record = SurveyForRead::findOne(['id' => $id]);
        return new SurveyForSurveyJs(new SurveyId($record->id), $record->config);
    }

    public function retrieveForDashboarding(SurveyId $adminSurveyId, SurveyId $dataSurveyId): HeramsVariableSet
    {
        $adminSurvey = Survey::findOne(['id' => $adminSurveyId->getValue()]);
        $dataSurvey = Survey::findOne(['id' => $dataSurveyId->getValue()]);
        $variables = [];

        $adminVariables = $this->surveyParser->parseHeramsSurveyStructure($adminSurvey->config);
        $dataVariables = $this->surveyParser->parseSurveyStructure($dataSurvey->config);

        $allVariables = new VariableSet(...toArray(chain($dataVariables->getVariables(), $adminVariables->getVariables())));
        return new HeramsVariableSet($allVariables, $adminVariables->colorMap);
    }

    public function retrieveForUpdate(SurveyId $id): UpdateForm
    {
        $record = Survey::findOne(['id' => $id]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $model = new UpdateForm($id);
        $model->config = $record->config;

        return $model;
    }

    public function search(SurveySearch $model): DataProviderInterface
    {
        $query = SurveyRead::find();

        if ($model->validate()) {
            $query->andFilterWhere(['id' => $model->id]);
            $query->andFilterWhere(['like', 'JSON_EXTRACT(`config`, "$.title")', $model->title]);
        }

        $dataProvider = new HydratedActiveDataProvider(
            fn(Survey $survey) => $this->hydrator->hydrateConstructor($survey, SurveyForList::class),
            [
                'query' => $query,
            ]
        );

        /**
         * Optimize total count since we don't have Survey specific permissions.
         * If this ever changes, pagination may break but permission checking will not
         */
        $dataProvider->totalCount = fn(QueryInterface $query) => (int) $query->count();

        return $dataProvider;
    }

    public function update(UpdateForm $model): SurveyId
    {
        $record = Survey::findOne(['id' => $model->getSurveyId()]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($model, $record);
        if (!$record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }
}
