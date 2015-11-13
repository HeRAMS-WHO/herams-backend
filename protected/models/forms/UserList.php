<?php

namespace prime\models\forms;

use prime\models\ar\User;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;

class UserList extends \prime\models\ar\UserList
{
    public $userIds;

    public function afterFind()
    {
        parent::afterFind();
        $this->userIds = ArrayHelper::getColumn($this->users, 'id');
    }


    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'userIds' => \Yii::t('app', 'Users')
            ]
        );
    }

    public function getUserOptions()
    {
        return ArrayHelper::map(
            User::find()->andWhere(['not', ['id' => app()->user->id]])->all(),
            'id',
            'name'
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['userIds'], 'required'],
                [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true]
            ]
        );
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $result = parent::save($runValidation, $attributeNames);
        $this->unlinkAll('users', true);
        foreach($this->userIds as $userId) {
            $this->link('users', User::findOne($userId));
        }
        return $result;
    }


}