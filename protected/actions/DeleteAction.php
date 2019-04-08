<?php


namespace prime\actions;


use prime\models\permissions\Permission;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\web\User;

class DeleteAction extends Action
{
    /** @var ActiveQueryInterface */
    public $query;
    public $permission = Permission::PERMISSION_ADMIN;

    public $redirect;

    public function init()
    {
        parent::init();
        if (!$this->query instanceof ActiveQueryInterface) {
            throw new InvalidConfigException('Query must be instance of ActiveRecordInterface');
        }
    }

    public function run(
        User $user,
        Session $session,
        int $id
    ) {
        /** @var ActiveRecordInterface $model */
        $model = $this->query->andWhere(['id' => $id])->one();
        if (!isset($model)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can($this->permission, $model)) {
            throw new ForbiddenHttpException();
        }

        if ($model->delete() === false) {
            $session->setFlash('fail', 'Deletion failed');
        } else {
            $session->setFlash('fail', 'Deletion successful');
        }

        return $this->controller->redirect($this->redirect);
    }
}