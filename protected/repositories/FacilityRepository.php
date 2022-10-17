<?php

declare(strict_types=1);

namespace prime\repositories;

use Collecthor\SurveyjsParser\ArrayDataRecord;
use prime\helpers\CanCurrentUserWrapper;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\SurveyRepositoryInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\read\Facility as FacilityReadRecord;
use prime\models\ar\Workspace;
use prime\models\facility\FacilityForList;
use prime\models\forms\facility\CreateForm;
use prime\models\forms\facility\UpdateForm;
use prime\models\forms\facility\UpdateSituationForm;
use prime\models\search\FacilitySearch;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\modules\Api\models\NewFacility;
use prime\modules\Api\models\UpdateFacility;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class FacilityRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
        private ActiveRecordHydratorInterface $activeRecordHydrator,
        private SurveyRepositoryInterface $surveyRepository,
        private SurveyResponseRepository $surveyResponseRepository,
        private WorkspaceRepository $workspaceRepository,
        private HeramsVariableSetRepository $heramsVariableSetRepository
    ) {
    }

    public function getWorkspaceId(FacilityId $id): WorkspaceId
    {
        return new WorkspaceId(Facility::find()->andWhere([
            'id' => $id,
        ])->select('workspace_id')->scalar());
    }

    private function hydrateFacilityFromDataResponseData(SurveyForSurveyJsInterface $survey, Facility $facility, array $data): void
    {
        // TODO We need to look at the survey structure to find out which expressions are required to be checked and evaluated
        // This can mean we need the historic records as well.

        $facility->can_receive_situation_update = (bool) ($data['canReceiveSituationUpdate'] ?? true);
        $facility->use_in_dashboarding = (bool) ($data['useInDashboarding'] ?? true);
        $facility->use_in_list = (bool) ($data['useInList'] ?? true);

        $facility->data = $data;
    }

    private function hydrateFacilityFromAdminResponseData(SurveyForSurveyJsInterface $survey, Facility $facility, array $data): void
    {
        // TODO We need to look at the survey structure to find out which answers map to which facility fields
        $this->hydrator->hydrateFromRequestArray($facility, $data);
        $facility->admin_data = $data;
    }

    public function create(NewFacility $model): FacilityId
    {
        $record = new Facility();

        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId((string) $record->id);
    }

    public function createFormModel(WorkspaceForCreateOrUpdateFacility $workspace): CreateForm
    {
        $model = new CreateForm(
            $workspace->getLanguages(),
            $this->surveyRepository->retrieveAdminSurveyForWorkspaceForSurveyJs($workspace->getId()),
            $workspace->getId(),
        );
        $record = new Facility([
            'workspace_id' => $workspace->getId()->getValue(),
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_CREATE);
        return $model;
    }

    public function retrieveForUpdate(FacilityId $id): UpdateFacility
    {
        $record = Facility::findOne([
            'id' => $id,
        ]);

        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $model = new UpdateFacility($id);

        $this->activeRecordHydrator->hydrateRequestModel($record, $model);
        return $model;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function retrieveForUpdateSituation(FacilityId $facilityId): UpdateSituationForm
    {
        /** @var null|Facility $record */
        $record = Facility::find()->andWhere([
            'id' => $facilityId,
        ])->one();
        if (! $record->canReceiveSituationUpdate()) {
            throw new ForbiddenHttpException('Situation cannot be updated.');
        }
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_SURVEY_DATA);

        $workspaceId = new WorkspaceId($record->workspace_id);
        $workspace = $this->workspaceRepository->retrieveForNewFacility($workspaceId);

        $form = new UpdateSituationForm(
            $facilityId,
            $workspace->getLanguages(),
            $this->surveyRepository->retrieveDataSurveyForWorkspaceForSurveyJs($workspaceId)
        );
        $surveyResponse = $this->surveyResponseRepository->retrieveDataSurveyResponseForFacilitySituationUpdate($facilityId);
        $form->data = $surveyResponse ? $surveyResponse->getData() : [];
        return $form;
    }

    public function saveUpdate(UpdateForm $model): FacilityId
    {
        $record = Facility::findOne([
            'id' => $model->getFacilityId(),
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $transaction = Facility::getDb()->beginTransaction();

        $this->hydrateFacilityFromAdminResponseData($model->getSurvey(), $record, $model->data);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }

        $createSurveyResponse = $this->surveyResponseRepository->createFormModel($model->getSurvey()->getId(), $model->getFacilityId());
        $createSurveyResponse->data = $model->data;
        $this->surveyResponseRepository->create($createSurveyResponse);

        $transaction->commit();

        return new FacilityId((string) $record->id);
    }

    public function retrieveActiveRecord(FacilityId $id): Facility|null
    {
        return Facility::findOne([
            'id' => $id,
        ]);
    }

    public function saveUpdateSituation(UpdateSituationForm $model): FacilityId
    {
        $record = Facility::findOne([
            'id' => $model->getFacilityId(),
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_SURVEY_DATA);

        $transaction = Facility::getDb()->beginTransaction();

        $this->hydrateFacilityFromDataResponseData($model->getSurvey(), $record, $model->data);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }

        $createSurveyResponse = $this->surveyResponseRepository->createFormModel($model->getSurvey()->getId(), $model->getFacilityId());
        $createSurveyResponse->data = $model->data;
        $this->surveyResponseRepository->create($createSurveyResponse);

        $transaction->commit();

        return new FacilityId((string) $record->id);
    }

    /**
     * @return iterable<Facility>
     */
    public function searchInProject(ProjectId $id): iterable
    {
        $workspaceIds = Workspace::find()
            ->select('id')
            ->andWhere([
                'project_id' => $id->getValue(),
            ])->column();
        yield from Facility::find()->andWhere([
            'workspace_id' => $workspaceIds,
        ])->each();
    }

    /**
     * @return list<FacilityReadRecord>
     */
    public function retrieveForWorkspace(WorkspaceId $id): array
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->checkPermission($workspace, Permission::PERMISSION_LIST_FACILITIES);
        $query = FacilityReadRecord::find()
            ->inWorkspace($id)
            ->useInList()
        ;
        return $query->all();
    }

    public function searchInWorkspace(WorkspaceId $id, null|FacilitySearch $model): DataProviderInterface
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);

        $query = FacilityReadRecord::find()->andWhere([
            'use_in_list' => true,
        ]);

        $query->andFilterWhere([
            'workspace_id' => $id->getValue(),
        ]);

        if ($model->validate()) {
            $query->andFilterWhere(['like', 'name', $model->name]);
            $query->andFilterWhere([
                'id' => $model->id,
            ]);
        }

        $models = array_map(fn (FacilityReadRecord $facility) => new FacilityForList(
            new FacilityId((string) $facility->id),
            $facility->name,
            $facility->dataSurveyResponseCount,
            new ArrayDataRecord([...($facility->data ?? []), ...($facility->admin_data ?? [])]),
            new CanCurrentUserWrapper($this->accessCheck, $facility),
        ), $query->all());

        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
        ]);

        $dataProvider->setPagination([
            'pageSize' => 15,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                //                FacilityForList::ID,
                //                FacilityForList::NAME,
                //                FacilityForList::ALTERNATIVE_NAME,
                //                FacilityForList::CODE,
                //                FacilityForList::RESPONSE_COUNT,
            ],
        ]);

        return $dataProvider;
    }

    public function retrieveForTabMenu(FacilityId $id): FacilityForTabMenu
    {
        $facility = FacilityReadRecord::findOne([
            'id' => (int) $id->getValue(),
        ]);
        if (! isset($facility)) {
            throw new NotFoundHttpException();
        }
        return new \prime\models\facility\FacilityForTabMenu(
            $id,
            $facility->name ?? 'test_name',
            new ProjectId($facility->workspace->project_id),
            $facility->workspace->project->title,
            new WorkspaceId($facility->workspace_id),
            $facility->workspace->title,
            $facility->dataSurveyResponseCount,
            $facility->adminSurveyResponseCount,
            $facility->canReceiveSituationUpdate(),
            new CanCurrentUserWrapper($this->accessCheck, $facility)
        );
    }
}
