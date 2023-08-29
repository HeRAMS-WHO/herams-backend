<?php

declare(strict_types=1);

namespace herams\common\domain\survey;

use Collecthor\DataInterfaces\VariableSetInterface;
use Collecthor\SurveyjsParser\VariableSet;
use herams\api\models\NewSurvey;
use herams\api\models\UpdateSurvey;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\models\Permission;
use herams\common\models\Survey as SurveyModel;
use herams\common\models\Workspace;
use herams\common\traits\RepositorySave;
use herams\common\values\SurveyId;
use herams\common\values\WorkspaceId;
use prime\helpers\HeramsVariableSet;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\survey\UpdateForm;
use prime\models\survey\SurveyForSurveyJs;
use yii\base\InvalidArgumentException;
use function iter\chain;
use function iter\toArray;

final class SurveyRepository implements SurveyRepositoryInterface
{
    use RepositorySave;

    public function __construct(
        private readonly SurveyParser $surveyParser,
        private readonly AccessCheckInterface $accessCheck,
        private readonly ActiveRecordHydratorInterface $activeRecordHydrator,
        private readonly ModelHydrator $hydrator,
    ) {
    }

    public function create(NewSurvey $model): SurveyId
    {
        $record = new Survey();
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_CREATE);
        $this->hydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }

    public function retrieveAdminSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface
    {
        $workspace = Workspace::findOne([
            'id' => $workspaceId,
        ]);
        return $this->retrieveForSurveyJs($workspace->project->getAdminSurveyId());
    }

    public function retrieveDataSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface
    {
        $workspace = Workspace::findOne([
            'id' => $workspaceId,
        ]);
        return $this->retrieveForSurveyJs($workspace->project->getDataSurveyId());
    }

    public function retrieveForSurveyJs(SurveyId $id): SurveyForSurveyJsInterface
    {
        $record = Survey::findOne([
            'id' => $id,
        ]);
        return new SurveyForSurveyJs(new SurveyId($record->id), $record->config);
    }

    public function retrieveVariableSet(SurveyId $adminSurveyId, SurveyId $dataSurveyId): HeramsVariableSet
    {
        $adminSurvey = Survey::findOne([
            'id' => $adminSurveyId->getValue(),
        ]);
        $dataSurvey = Survey::findOne([
            'id' => $dataSurveyId->getValue(),
        ]);

        $adminVariables = $this->surveyParser->parseHeramsSurveyStructure($adminSurvey->config);
        $dataVariables = $this->surveyParser->parseSurveyStructure($dataSurvey->config);

        $allVariables = new VariableSet(...toArray(chain($dataVariables->getVariables(), $adminVariables->getVariables())));
        return new HeramsVariableSet($allVariables, $adminVariables->colorMap);
    }

    public function retrieveSimpleVariableSet(SurveyId $surveyId): VariableSetInterface
    {
        return $this->surveyParser->parseSurveyStructure(Survey::findOne([
            'id' => $surveyId->getValue(),
        ])->config);
    }

    public function getById(SurveyId $id): SurveyModel
    {
        return SurveyModel::findOne([
            'id' => $id,
        ]);
    }

    public function retrieveForUpdate(SurveyId $id): Survey
    {
        $record = Survey::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        return $record;
    }

    /**
     * @return \Generator<int, SurveyForList>
     */
    public function retrieveAll(): iterable
    {
        /** @var Survey $survey */
        foreach (Survey::find()->all() as $survey) {
            if ($this->accessCheck->checkPermission($survey, Permission::PERMISSION_READ)) {
                yield $this->hydrator->hydrateConstructor($survey, SurveyForList::class);
            }
        }
    }

    public function update(UpdateForm $model): SurveyId
    {
        $record = Survey::findOne([
            'id' => $model->getSurveyId(),
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }

    public function save(UpdateSurvey $model): SurveyId
    {
        $record = Survey::findOne([
            'id' => $model->id,
        ]);
        $this->internalSave($record, $model);
        return new SurveyId($record->id);
    }
}
