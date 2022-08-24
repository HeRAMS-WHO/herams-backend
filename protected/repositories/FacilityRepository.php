<?php

declare(strict_types=1);

namespace prime\repositories;

use Carbon\Carbon;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\SurveyjsParser\ArrayRecord;
use prime\components\HydratedActiveDataProvider;
use prime\helpers\CanCurrentUserWrapper;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\CanCurrentUser;
use prime\interfaces\facility\FacilityForBreadcrumbInterface;
use prime\interfaces\FacilityForResponseCopy;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\SurveyRepositoryInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\read\Facility as FacilityReadRecord;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\facility\FacilityForBreadcrumb;
use prime\models\facility\FacilityForList;
use prime\models\forms\facility\CreateForm;
use prime\models\forms\facility\UpdateForm;
use prime\models\forms\facility\UpdateSituationForm;
use prime\models\forms\ResponseFilter;
use prime\models\forms\workspace\UpdateForLimesurvey as WorkspaceUpdateForLimesurvey;
use prime\models\search\FacilitySearch;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\modules\Api\models\NewFacility;
use prime\modules\Api\models\UpdateFacility;
use prime\modules\Api\models\UpdateWorkspace;
use prime\objects\enums\FacilityTier;
use prime\objects\enums\ProjectType;
use prime\objects\HeramsCodeMap;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\ProjectId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use yii\base\InvalidArgumentException;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;
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
        return new WorkspaceId(Facility::find()->andWhere(['id' => $id])->select('workspace_id')->scalar());
    }
    /**
     * TODO Limesurvey deprecation: remove
     */
    public function isOfProjectType(FacilityId $facilityId, ProjectType $type): bool
    {
        return (
            $type->equals(ProjectType::limesurvey())
            && preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $facilityId->getValue())
        ) || (
            $type->equals(ProjectType::surveyJs())
            && is_numeric($facilityId->getValue())
        );
    }

    /**
     * TODO Limesurvey deprecation: remove
     */
    public function retrieveForResponseCopy(FacilityId $id): FacilityForResponseCopy
    {
        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $responseQuery = ResponseForLimesurvey::find()->andWhere([
                'hf_id' => $matches['hf_id'],
                'survey_id' => $matches['survey_id'],
            ])->orderBy([
                'id' => SORT_DESC,
            ]);
        // TODO: permission checking for HFs defined in LS.
        } else {
            throw new InvalidArgumentException('Response copy only works for Limesurvey projects.');
        }

        $response = $responseQuery->limit(1)->one();

        return new \prime\models\facility\FacilityForResponseCopy(new ResponseId($response->auto_increment_id));
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

    public function retrieveActiveRecord(FacilityId $id): Facility
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

    public function searchInWorkspace(WorkspaceId $id, FacilitySearch $model): DataProviderInterface
    {
        $workspace = Workspace::findOne([
            'id' => $id->getValue(),
        ]);

        if ($workspace instanceof WorkspaceForLimesurvey) {
            $filter = new ResponseFilter($workspace->project->getSurvey(), new HeramsCodeMap());

            $limesurveyData = [];
            /** @var ResponseForLimesurvey $response */
            foreach ($filter->filterQuery($workspace->getResponses())->each() as $response) {
                $limesurveyData[$response->hf_id] = $this->createFromResponse($response);
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $limesurveyData,
            ]);
        } else {
            // Get survey as well for variable interpretation
            $variables = $this->heramsVariableSetRepository->retrieveForProject(new ProjectId($workspace->project_id));

            $typeVariable = $variables->getFacilityTierVariable();
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

            $dataProvider = new HydratedActiveDataProvider(
                function (Facility $facility) use ($typeVariable) {
                    return new FacilityForList(
                        new FacilityId((string) $facility->id),
                        $facility->name,
                        $facility->alternative_name,
                        $facility->code,
                        $facility->latitude,
                        $facility->longitude,
                        $facility->dataSurveyResponseCount,
                        $typeVariable->getTier(new ArrayRecord($facility->data ?? [], 100000000 + $facility->id, Carbon::now(), Carbon::now())),
                        new CanCurrentUserWrapper($this->accessCheck, $facility),
                    );
                },
                [
                    'query' => $query,
                    /**
                     * Optimize total count since we don't have HF specific permissions.
                     * If this ever changes, pagination may break but permission checking will not
                     */
                    'totalCount' => fn (QueryInterface $query) => (int) $query->count(),
                ]
            );
        }

        $dataProvider->setPagination([
            'pageSize' => 15,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                FacilityForList::ID,
                FacilityForList::NAME,
                FacilityForList::ALTERNATIVE_NAME,
                FacilityForList::CODE,
                FacilityForList::RESPONSE_COUNT,
            ],
        ]);

        return $dataProvider;
    }

    private function createFromResponse(ResponseForLimesurvey $response): FacilityForList
    {
        $latitude = $response->getLatitude();
        $longitude = $response->getLongitude();
        return new FacilityForList(
            new FacilityId("LS_{$response->survey_id}_{$response->hf_id}"),
            $response->name,
            null,
            $response->hf_id,
            $latitude,
            $longitude,
            // This is very inefficient for the response list; for now we accept it.
            (int) ResponseForLimesurvey::find()->andWhere([
                'hf_id' => $response->hf_id,
                'survey_id' => $response->survey_id,
            ])->count()
        );
    }

    public function retrieveForBreadcrumb(FacilityId $id): FacilityForBreadcrumbInterface
    {
        $facility = Facility::findOne([
            'id' => (int) $id->getValue(),
        ]);
        return new FacilityForBreadcrumb($facility);
    }


    public function retrieveForTabMenu(FacilityId $id): FacilityForTabMenu
    {
        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $response = ResponseForLimesurvey::find()
                ->with('workspace')
                ->andWhere([
                    'hf_id' => $matches['hf_id'],
                    'survey_id' => $matches['survey_id'],
                ])
                ->orderBy([
                    'date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->limit(1)
                ->one();
            if (! isset($response)) {
                throw new NotFoundHttpException();
            }
            return new \prime\models\facility\FacilityForTabMenu(
                $id,
                $response->name,
                new ProjectId($response->workspace->project_id),
                $response->workspace->project->title,
                new WorkspaceId($response->workspace_id),
                $response->workspace->title,
                (int) ResponseForLimesurvey::find()->andWhere([
                    'hf_id' => $response->hf_id,
                    'survey_id' => $response->survey_id,
                ])->count(),
                0,
                true,
                // Access checker for LS based data.
                new class() implements CanCurrentUser {
                    public function canCurrentUser(string $permission): bool
                    {
                        return true;
                    }
                }
            );
        } else {
            $facility = FacilityReadRecord::findOne([
                'id' => (int) $id->getValue(),
            ]);
            return new \prime\models\facility\FacilityForTabMenu(
                $id,
                $facility->name,
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
}
