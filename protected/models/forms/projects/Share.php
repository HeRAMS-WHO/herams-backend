<?php

namespace prime\models\forms\projects;

use prime\models\permissions\Permission;
use prime\models\permissions\UserProject;
use prime\models\Project;
use prime\models\User;
use yii\base\Model;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;

class Share extends Model {

    public $userIds;
    public $permission;
    public $projectId;

    public function createRecords()
    {
        $result = false;
        if($this->validate()) {
            $result = true;
            $transaction = app()->db->beginTransaction();

            foreach($this->userIds as $userId)
            {
                $userProject = new UserProject([
                    'projectId' => $this->projectId,
                    'userId' => $userId,
                    'permission' => $this->permission
                ]);
                $result = $result && $userProject->save();
            }

            if($result) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        }
        return $result;
    }

    public function getProject()
    {
        return Project::findOne($this->projectId);
    }

    public function getUsers()
    {
        return User::findAll(['id' => $this->userIds]);
    }

    public function rules() {
        return [
            [['userIds', 'permission', 'projectId'], 'required'],
            [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['projectId'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['permission'], RangeValidator::class, 'range' => array_keys(Permission::permissionLabels())]
        ];
    }
}