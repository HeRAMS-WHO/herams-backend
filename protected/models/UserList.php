<?php

namespace prime\models;

use prime\components\ActiveRecord;
use yii\validators\ExistValidator;
use yii\validators\StringValidator;

class UserList extends ActiveRecord
{
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('{{%user_list_user}}', ['user_list_id' => 'id']);
    }

    public function rules()
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['name'], StringValidator::class]
        ];
    }

    public function userCan($operation, User $user = null)
    {
        return $this->user_id == $user->id || parent::userCan($operation, $user);
    }
}