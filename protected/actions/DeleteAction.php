<?php

namespace prime\actions;

use herams\common\models\PermissionOld;
use prime\components\NotificationService;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class DeleteAction extends Action
{
    public ActiveQueryInterface $query;

    /**
     * @var string|\Closure
     */
    public $permission = PermissionOld::PERMISSION_DELETE;

    public $redirect;

    public function init()
    {
        parent::init();
        if (! isset($this->query)) {
            throw new InvalidConfigException('Query must be instance of ActiveRecordInterface');
        }
    }

    public function run(
        User $user,
        NotificationService $notificationService,
        int $id
    ) {
        if (! \Yii::$app->request->isDelete) {
            throw new MethodNotAllowedHttpException();
        }
        /** @var ActiveRecordInterface| null $model */
        $model = $this->query->andWhere([
            'id' => $id,
        ])->one();
        if (! isset($model)) {
            throw new NotFoundHttpException();
        }
        if (
            is_string($this->permission) && ! $user->can($this->permission, $model)
            || ($this->permission instanceof \Closure && ! call_user_func($this->permission, $user, $model))
        ) {
            throw new ForbiddenHttpException();
        }
        if ($model->delete() === false) {
            $notificationService->error('Deletion failed');
        } else {
            $notificationService->success('Deletion successful');
        }

        if ($this->redirect instanceof \Closure) {
            $redirect = call_user_func($this->redirect, $model);
        } else {
            $redirect = $this->redirect;
        }

        return $this->controller->redirect($redirect ?? [$this->controller->defaultAction]);
    }
}
