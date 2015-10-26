<?php

namespace prime\models\forms\projects;

use prime\models\permissions\Permission;
use prime\models\permissions\UserProject;
use prime\models\Project;
use prime\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;

class Share extends Model {

    public $userIds;
    public $permission;
    public $projectId;

    public function attributeLabels()
    {
        return [
            'userIds' => \Yii::t('app', 'Users')
        ];
    }

    public function createRecords()
    {
        $result = false;
        if($this->validate()) {
            $result = true;
            $transaction = app()->db->beginTransaction();

            foreach($this->userIds as $userId)
            {
                $result = $result && UserProject::grant(User::findOne($userId), $this->getProject(), $this->permission);
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

    public function getProject()
    {
        return Project::findOne($this->projectId);
    }

    public function getUserOptions()
    {
        return ArrayHelper::map(User::find()->andWhere(['not', ['id' => app()->user->id]])->all(), 'id', 'name');
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