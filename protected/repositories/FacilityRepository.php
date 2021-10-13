<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\components\CompositeDataProvider;
use prime\components\HydratedActiveDataProvider;
use prime\helpers\CanCurrentUserWrapper;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\CanCurrentUser;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\interfaces\facility\FacilityForBreadcrumbInterface;
use prime\interfaces\FacilityForResponseCopy;
use prime\interfaces\FacilityForTabMenu;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\read\Facility as FacilityReadRecord;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\facility\FacilityForBreadcrumb;
use prime\models\facility\FacilityForList;
use prime\models\forms\NewFacility as FacilityForm;
use prime\models\forms\ResponseFilter;
use prime\models\forms\UpdateFacility;
use prime\models\search\FacilitySearch;
use prime\objects\HeramsCodeMap;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\Point;
use prime\values\ProjectId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use Ramsey\Uuid\Uuid;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;
use yii\web\NotFoundHttpException;

class FacilityRepository implements CreateModelRepositoryInterface
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
        private WorkspaceRepository $workspaceRepository
    ) {
    }

    public function retrieveForResponseCopy(FacilityId $id): FacilityForResponseCopy
    {
        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $responseQuery = ResponseForLimesurvey::find()->andWhere([
                'hf_id' => $matches['hf_id'],
                'survey_id' => $matches['survey_id']
            ]);
            // TODO: permission checking for HFs defined in LS.
        } else {
            $record = Facility::findOne(['id' => $id]);
            $this->accessCheck->requirePermission($record, Permission::PERMISSION_ADD_RESPONSE_TO_FACILITY);
            $responseQuery = $record->getResponses();
        }

        $response = $responseQuery->orderBy(['updated_at' => 'desc'])->limit(1)->one();


        return new \prime\models\facility\FacilityForResponseCopy(new ResponseId($response->auto_increment_id));
    }

    public function create(Model|FacilityForm $model): FacilityId
    {
        requireParameter($model, FacilityForm::class, 'model');
        $record = new Facility();
        $record->workspace_id = $model->getWorkspace()->id()->getValue();
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId((string) $record->id);
    }

    public function createFormModel(IntegerId $id): FacilityForm
    {
        $model = new FacilityForm(new WorkspaceId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function retrieveForWrite(FacilityId $id): UpdateFacility
    {
        /** @var null|Facility $facility */
        $facility = Facility::find()->andWhere(['id' => $id])->one();
        if (!isset($facility)) {
            throw new NotFoundHttpException();
        }
        $workspace = $this->workspaceRepository->retrieveForNewFacility(new WorkspaceId($facility->workspace_id));

        $form = new UpdateFacility($id, $workspace);
        $form->data = [
            'name' => $facility->name
        ];
        $this->hydrator->hydrateFromActiveRecord($form, $facility);
        return $form;
    }

    public function save(UpdateFacility $facility): FacilityId
    {
        $record = Facility::findOne(['id' => $facility->getId()]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($record, $facility);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId((string) $record->id);
    }

    public function searchInWorkspace(WorkspaceId $id, FacilitySearch $model): DataProviderInterface
    {
        $workspace = Workspace::findOne(['id' => $id->getValue()]);

        if ($workspace instanceof WorkspaceForLimesurvey) {
            $filter = new ResponseFilter($workspace->project->getSurvey(), new HeramsCodeMap());

            $limesurveyData = [];
            /** @var \prime\models\ar\ResponseForLimesurvey $response */
            foreach ($filter->filterQuery($workspace->getResponses())->each() as $response) {
                $limesurveyData[$response->hf_id] = $this->createFromResponse($response);
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $limesurveyData
            ]);
        } else {
            $query = FacilityReadRecord::find();

            $query->andFilterWhere(['workspace_id' => $id->getValue()]);

            if ($model->validate()) {
                $query->andFilterWhere(['like', 'name', $model->name]);
                $query->andFilterWhere(['id' => $model->id]);
            }

            $dataProvider = new HydratedActiveDataProvider(
                fn(Facility $facility) => $this->hydrator->hydrateConstructor($facility, FacilityForList::class),
                [
                    'query' => $query,
                    /**
                     * Optimize total count since we don't have HF specific permissions.
                     * If this ever changes, pagination may break but permission checking will not
                     */
                    'totalCount' => fn(QueryInterface $query) => (int) $query->count(),
                ]
            );
        }

        $dataProvider->setPagination([
            'pageSize' => 15,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                FacilityForList::UUID,
                FacilityForList::ID,
                FacilityForList::NAME,
                FacilityForList::ALTERNATIVE_NAME,
                FacilityForList::CODE,
                FacilityForList::RESPONSE_COUNT
            ]
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
            isset($latitude, $longitude) ?  new Point(null, $latitude, $longitude) : null,
            Uuid::fromBytes(str_pad($response->hf_id, 16)),
            // This is very inefficient for the response list; for now we accept it.
            (int) ResponseForLimesurvey::find()->andWhere(['hf_id' => $response->hf_id, 'survey_id' => $response->survey_id])->count()
        );
    }

    public function retrieveForBreadcrumb(FacilityId $id): FacilityForBreadcrumbInterface
    {
        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $response = ResponseForLimesurvey::findOne([
                'hf_id' => $matches['hf_id'],
                'survey_id' => $matches['survey_id']
            ]);

            if (!isset($response)) {
                throw new NotFoundHttpException();
            }

            return new FacilityForBreadcrumb($response);
        } else {
            $facility = Facility::findOne(['id' => (int) $id->getValue()]);
            return new FacilityForBreadcrumb($facility);
        }
    }

    public function retrieveForRead(FacilityId $id): FacilityForList
    {

        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $response = ResponseForLimesurvey::findOne([
                'hf_id' => $matches['hf_id'],
                'survey_id' => $matches['survey_id']
            ]);
            if (!isset($response)) {
                throw new NotFoundHttpException();
            }
            return $this->createFromResponse($response);
        } else {
            $facility = FacilityReadRecord::findOne(['id' => (int) $id->getValue()]);
            return $this->hydrator->hydrateConstructor($facility, FacilityForList::class);
        }
    }

    public function retrieveForTabMenu(FacilityId $id): FacilityForTabMenu
    {

        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $response = ResponseForLimesurvey::find()
                ->with('workspace')
                ->andWhere([
                    'hf_id' => $matches['hf_id'],
                    'survey_id' => $matches['survey_id']
                ])
                ->orderBy(['date' => SORT_DESC, 'updated_at' => SORT_DESC])
                ->limit(1)
                ->one();
            if (!isset($response)) {
                throw new NotFoundHttpException();
            }
            return new \prime\models\facility\FacilityForTabMenu(
                $id,
                $response->name,
                new ProjectId($response->workspace->project_id),
                $response->workspace->project->title,
                new WorkspaceId($response->workspace_id),
                $response->workspace->title,
                (int) ResponseForLimesurvey::find()->andWhere(['hf_id' => $response->hf_id, 'survey_id' => $response->survey_id])->count(),
                // Access checker for LS based data.
                new class implements CanCurrentUser {
                    public function canCurrentUser(string $permission): bool
                    {
                        return true;
                    }
                }
            );
        } else {
            $facility = FacilityReadRecord::findOne(['id' => (int) $id->getValue()]);
            return new \prime\models\facility\FacilityForTabMenu(
                $id,
                $facility->name,
                new ProjectId($facility->workspace->project_id),
                $facility->workspace->project->title,
                new WorkspaceId($facility->workspace_id),
                $facility->workspace->title,
                (int) $facility->getResponses()->count(),
                new CanCurrentUserWrapper($this->accessCheck, $facility)
            );
        }
    }
}
