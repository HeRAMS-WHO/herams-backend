<?php

namespace prime\models\forms\projects;

use prime\models\permissions\Permission;
use prime\models\permissions\UserProject;
use prime\models\Project;
use prime\models\User;
use prime\models\UserList;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;

class Share extends Model {

    public $userIds;
    public $userListIds;
    public $permission;
    public $projectId;

    public function attributeLabels()
    {
        return [
            'userIds' => \Yii::t('app', 'Users'),
            'userListIds' => \Yii::t('app', 'User lists')
        ];
    }

    public function createRecords()
    {
        $result = false;
        if($this->validate()) {
            $result = true;
            $transaction = app()->db->beginTransaction();

            foreach($this->getUsers()->all() as $user) {
                $result = $result && UserProject::grant($user, $this->getProject(), $this->permission);
            }

            if($result) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        }
        return $result;
    }

    public function getPermissionOptions()
    {
        return Permission::permissionLabels();
    }

    /**
     * @return null|Project
     */
    public function getProject()
    {
        return Project::findOne($this->projectId);
    }

    public function getUserOptions()
    {
        return ArrayHelper::map(User::find()->andWhere(['not', ['id' => app()->user->id]])->all(), 'id', 'name');
    }

    public function getUserListOptions()
    {
        return ArrayHelper::map(app()->user->identity->userLists, 'id', 'name');
    }

    public function getUserLists()
    {
        return UserList::find()->where(['id' => $this->userListIds]);
    }

    public function getUsers()
    {
        $userIds = $this->userIds;

        /** @var UserList $userList */
        foreach($this->getUserLists()->all() as $userList){
            $userIds = ArrayHelper::merge($userIds, $userList->getUsers()->select('id')->column());
        }

        $userIds = array_diff($userIds, [$this->getProject()->owner_id]);

        return User::find()->where([
            'id' => $userIds
        ]);
    }

    public function rules() {
        return [
            [['permission', 'projectId'], 'required'],
            [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['userListIds'], ExistValidator::class, 'targetClass' => UserList::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['projectId'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['userIds', 'userListIds'], DefaultValueValidator::class, 'value' => []],
            [['permission'], RangeValidator::class, 'range' => array_keys(Permission::permissionLabels())]
        ];
    }
}