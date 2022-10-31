<?php

declare(strict_types=1);

namespace prime\repositories;

use Collecthor\DataInterfaces\VariableSetInterface;
use Collecthor\SurveyjsParser\VariableSet;
use prime\helpers\HeramsVariableSet;
use prime\helpers\ModelHydrator;
use prime\helpers\SurveyParser;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\SurveyRepositoryInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Survey as SurveyForRead;
use prime\models\ar\read\Survey as SurveyRead;
use prime\models\ar\Survey;
use prime\models\ar\Workspace;
use prime\models\forms\survey\CreateForm;
use prime\models\forms\survey\UpdateForm;
use prime\models\survey\SurveyForList;
use prime\models\survey\SurveyForSurveyJs;
use prime\modules\Api\models\UpdateSurvey;
use prime\traits\RepositorySave;
use prime\values\SurveyId;
use prime\values\WorkspaceId;
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

    public function create(CreateForm $model): SurveyId
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
        $record = SurveyForRead::findOne([
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

    public function retrieveForUpdate(SurveyId $id): Survey
    {
        $record = Survey::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        return $record;    }

    public function retrieveAll(): iterable
    {
        foreach (SurveyRead::find()->all() as $survey) {
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
