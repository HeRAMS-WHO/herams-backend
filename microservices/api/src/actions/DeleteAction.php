<?php

declare(strict_types=1);

namespace herams\api\actions;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\ConditionallyDeletable;
use herams\common\models\PermissionOld;
use herams\common\queries\ActiveQuery;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class DeleteAction extends Action
{
    public ActiveQuery $query;

    public function __construct(
        $id,
        $controller,
        private AccessCheckInterface $accessCheck,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    /**
     * Deletes a model.
     * @param int $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run(int $id)
    {
        $model = $this->findModel($id);

        $this->accessCheck->requirePermission($model, PermissionOld::PERMISSION_DELETE);

        if ($model instanceof ConditionallyDeletable && ! $model->canBeDeleted()) {
            throw new ConflictHttpException(\Yii::t('app', 'This model can not currently be deleted'));
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        \Yii::$app->getResponse()->setStatusCode(204);
    }

    public function init(): void
    {
        if (! isset($this->query)) {
            throw new InvalidConfigException(get_class($this) . '::$query must be set.');
        }
    }

    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded.
     * @return ActiveRecordInterface the model found
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id): ActiveRecordInterface
    {
        $model = $this->query->andWhere([
            'id' => $id,
        ])->one();

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException("Object not found: $id");
    }
}
