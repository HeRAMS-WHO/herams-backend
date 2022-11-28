<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use herams\common\domain\facility\FacilityRead as FacilityReadRecord;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\variableSet\HeramsVariableSetRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\models\Permission;
use herams\common\models\Workspace;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\helpers\CanCurrentUserWrapper;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\facility\UpdateSituationForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

final class FacilityRepository
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

    public function create(NewFacility $model): FacilityId
    {
        $record = new Facility();

        $this->activeRecordHydrator->hydrateActiveRecord($model, $record);
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId((string) $record->id);
    }
    public function retrieveForUpdate(FacilityId $id): Facility
    {
        $record = Facility::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        return $record;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function retrieveForUpdateSituation(FacilityId $facilityId): UpdateSituationForm
    {
        /** @var null|FacilityRead $record */
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
     * @return iterable<FacilityRead>
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
            new WorkspaceId($facility->workspace_id),
            $facility->dataSurveyResponseCount,
            $facility->adminSurveyResponseCount,
            $facility->canReceiveSituationUpdate(),
            new CanCurrentUserWrapper($this->accessCheck, $facility)
        );
    }
}
