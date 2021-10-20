<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\components\HydratedActiveDataProvider;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\interfaces\response\ResponseForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\forms\NewFacility as FacilityForm;
use prime\models\forms\UpdateFacility;
use prime\models\response\ResponseForBreadcrumb;
use prime\models\response\ResponseForList;
use prime\models\response\ResponseForSurvey;
use prime\values\ExternalResponseId;
use prime\values\FacilityId;
use prime\values\IntegerId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use yii\base\Model;
use yii\data\DataProviderInterface;

class ResponseRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
        private WorkspaceRepository $workspaceRepository
    ) {
    }

    /**
     * Updates the reference to the LS response ID in our local response record.
     * @param ResponseId $id
     * @param ExternalResponseId $id
     */
    public function updateExternalId(ResponseId $id, ExternalResponseId $externalResponseId): void
    {
        $record = ResponseForLimesurvey::findOne(['auto_increment_id' => $id]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $record->id = $externalResponseId->getResponseId();
        $record->survey_id = $externalResponseId->getSurveyId();
        if (!$record->validate()) {
            throw new \RuntimeException("Failed to update internal record: ", print_r($record->errors, true));
        }
        $record->save(false);
    }

    public function duplicate(ResponseId $id): ResponseId
    {
        $record = ResponseForLimesurvey::findOne(['auto_increment_id' => $id]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_READ);
        // TODO: Add permission check for copying / writing.
        $record->auto_increment_id = null;
        $record->setIsNewRecord(true);
        if (!$record->validate()) {
            throw new \RuntimeException("Copy failed: " . print_r($record->errors, true));
        }
        $record->save(false);

        return new ResponseId($record->auto_increment_id);
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
        return new FacilityId($record->id);
    }

    public function createFormModel(IntegerId $id): FacilityForm
    {
        $model = new FacilityForm(new WorkspaceId($id->getValue()));
        $this->accessCheck->requirePermission($model, Permission::PERMISSION_CREATE);
        return $model;
    }

    public function retrieveForBreadcrumb(ResponseId $id): ForBreadcrumbInterface
    {
        $record = ResponseForLimesurvey::findOne(['id' => $id]);
        return new ResponseForBreadcrumb($record);
    }

    public function retrieveForSurvey(ResponseId $id): ResponseForSurvey
    {
        $response = ResponseForLimesurvey::findOne(['auto_increment_id' => $id]);
        $this->accessCheck->requirePermission($response, Permission::PERMISSION_WRITE);
        return new ResponseForSurvey(
            $id,
            $response->survey_id,
            $response->id,
            $response->workspace->token
        );
    }

    public function save(UpdateFacility $facility): FacilityId
    {
        $record = Facility::findOne(['id' => $facility->getId()]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($record, $facility);
        if (!$record->save()) {
            throw new \InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new FacilityId($record->id);

        // TODO: Implement save() method.
    }

    public function searchInFacility(FacilityId $id): DataProviderInterface
    {
        $query = \prime\models\ar\ResponseForLimesurvey::find();

        // Handle LS facility defined in responses.
        if (preg_match('/^LS_(?<survey_id>\d+)_(?<hf_id>.*)$/', $id->getValue(), $matches)) {
            $query->andWhere([
                'hf_id' => $matches['hf_id'],
                'survey_id' => $matches['survey_id'],
                'facility_id' => null
            ]);
        } else {
            $query->andWhere(['facility_id' => (int) $id->getValue()]);
        }

        return new HydratedActiveDataProvider(
            static function (ResponseForLimesurvey $response): \prime\interfaces\ResponseForList {
                return new ResponseForList($response);
            },
            [
                'sort' => [
                    'attributes' => [
                        'id',
                        'dateOfUpdate' => [
                            'asc' => ['date' => SORT_ASC],
                            'desc' => ['date' => SORT_DESC],
                            'default' => SORT_DESC,
                        ]
                    ]
                ],
                'query' => $query,
                'pagination' => false,
            ]
        );
    }
}