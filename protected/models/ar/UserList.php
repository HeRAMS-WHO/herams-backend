<?php

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\traits\LoadOneAuthTrait;
use yii\db\ActiveQuery;
use yii\validators\ExistValidator;
use yii\validators\StringValidator;

class UserList extends ActiveRecord
{
    use LoadOneAuthTrait;

    public function delete()
    {
        $this->unlinkAll('users', true);
        return parent::delete();
    }

    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
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

    public function userCan($operation, User $user)
    {
        $user = (isset($user)) ? (($user instanceof User) ? $user : User::findOne($user)) : app()->user->identity;

        $result = parent::userCan($operation, $user);
        if(!$result) {
            $result = $result || $this->user_id == $user->id;
            $result = $result || Permission::isAllowed($user, $this, $operation);
        }
        return $result;
    }
}