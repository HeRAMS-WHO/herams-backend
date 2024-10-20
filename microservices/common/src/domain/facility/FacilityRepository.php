<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use herams\common\domain\facility\Facility as FacilityModel;
use herams\common\domain\facility\FacilityRead as FacilityReadRecord;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\variableSet\HeramsVariableSetRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\models\PermissionOld;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\SurveyResponseId;
use herams\common\values\WorkspaceId;
use prime\helpers\CanCurrentUserWrapper;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
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
        //$record->date_of_update = $record->admin_data['date_of_update'] ?? null;
        if (! $record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId((int) $record->id);
    }

    public function retrieveForUpdate(FacilityId $id): Facility
    {
        $record = Facility::findOne([
            'id' => $id,
        ]);
        $this->accessCheck->requirePermission($record, PermissionOld::PERMISSION_WRITE);
        return $record;
    }

    public function retrieveFacility(FacilityId $id): array
    {
        $record = Facility::findOne([
            'id' => $id,
        ]);
        $amountOfSituationUpdates = SurveyResponse::find()->where([
            'facility_id' => $record->id,
            'response_type' => 'situation',
        ])->count();
        $amountOfAdminUpdates = SurveyResponse::find()->where([
            'facility_id' => $record->id,
            'response_type' => 'admin',
        ])->count();
        return [
            ...$record->toArray(), 
            'dataSurveyResponseCount' => $amountOfSituationUpdates, 
            'adminSurveyResponseCount' => $amountOfAdminUpdates
        ];
    }

    public function retrieveActiveRecord(FacilityId $id): Facility|null
    {
        return Facility::findOne([
            'id' => $id,
        ]);
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

    public function retrieveAllByWorkspaceId(WorkspaceId $id): array
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->checkPermission($workspace, PermissionOld::PERMISSION_LIST_FACILITIES);
        $query = FacilityReadRecord::find()
            ->inWorkspace($id);
        return $query->all();
    }

    /**
     * @return list<FacilityReadRecord>
     */
    public function retrieveByWorkspaceId(WorkspaceId $id): array
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->checkPermission($workspace, PermissionOld::PERMISSION_LIST_FACILITIES);
        $query = FacilityReadRecord::find()->andWhere([
            'or',
            ['!=', 'status', 'Deleted'],
            ['IS', 'status', null],
        ])
            ->inWorkspace($id);
        return $query->all();
    }

    public function deleteAll(array $condition): void
    {
        Facility::deleteAll($condition);
    }

    /**
     * @return list<FacilityReadRecord>
     */
    public function getByWorkspace(WorkspaceId $id): array
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->checkPermission($workspace, PermissionOld::PERMISSION_LIST_FACILITIES);
        return FacilityModel::find()->where([
            'workspace_id' => $id->getValue(),
        ])
            ->with('latestSurveyResponse')
            ->asArray()
            ->all();
    }
    
    public function retrieveForTabMenu(FacilityId $id): FacilityForTabMenu
    {
        $facility = FacilityReadRecord::findOne([
            'id' => (int) $id->getValue(),
        ]);
        if (! isset($facility)) {
            throw new NotFoundHttpException();
        }
        //print_r($facility->admin_data); exit;
        $name = $facility->admin_data['name']['en'] ?? '';
        if ($name == '') {
            $name = $facility->admin_data['name'] ?? '';
        }
        if ($name == '') {
            $name = $facility->data['name']['en'] ?? '';
        }
        return new \prime\models\facility\FacilityForTabMenu(
            $id,
            $name,
            new WorkspaceId($facility->workspace_id),
            $facility->dataSurveyResponseCount,
            $facility->adminSurveyResponseCount,
            $facility->canReceiveSituationUpdate(),
            new CanCurrentUserWrapper($this->accessCheck, $facility)
        );
    }

    public function deleteFacility(FacilityId $id)
    {
        $facility = Facility::findOne([
            'id' => $id,
        ]);

        //SurveyResponse::deleteAll(['facility_id' =>  $facility->id]);
        //$facility->delete();

        $surveyResponse = SurveyResponse::find()->where([
            'facility_id' => $facility->id,
        ])->all();
        foreach ($surveyResponse as $situationUpdate) {
            $situationUpdate->status = 'Deleted';
            $situationUpdate->update();
        }
        $surveyResponseId = new SurveyResponseId($situationUpdate->id);
        $this->surveyResponseRepository->propagateSurveysResponses($surveyResponseId);
        $facility->status = 'Deleted';
        $facility->update(false);
        return true;
    }
}
