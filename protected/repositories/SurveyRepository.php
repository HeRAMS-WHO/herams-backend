<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\components\HydratedActiveDataProvider;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\models\ar\read\Survey as SurveyRead;
use prime\models\ar\Survey;
use prime\models\search\SurveySearch;
use prime\models\survey\SurveyForCreate;
use prime\models\survey\SurveyForList;
use prime\models\survey\SurveyForUpdate;
use prime\values\SurveyId;
use yii\base\InvalidArgumentException;
use yii\data\DataProviderInterface;
use yii\db\QueryInterface;

class SurveyRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
        private ModelHydrator $hydrator,
    ) {
    }

    public function create(SurveyForCreate $model): SurveyId
    {
        $record = new Survey();
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_CREATE);
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }

    public function retrieveForUpdate(SurveyId $id): SurveyForUpdate
    {
        $record = Survey::findOne(['id' => $id]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);

        $model = new SurveyForUpdate($id);
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

    public function update(SurveyForUpdate $model): SurveyId
    {
        $record = Survey::findOne(['id' => $model->getSurveyId()]);
        $this->accessCheck->requirePermission($record, Permission::PERMISSION_WRITE);
        $this->hydrator->hydrateActiveRecord($record, $model);
        if (!$record->save()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($record->errors, true));
        }
        return new SurveyId($record->id);
    }
}
