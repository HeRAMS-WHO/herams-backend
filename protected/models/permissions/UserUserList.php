<?php

namespace prime\models\permissions;

use prime\components\ActiveQuery;
use prime\models\ar\Workspace;
use prime\models\ar\User;
use prime\models\ar\UserList;
use yii\helpers\ArrayHelper;

/**
 * Class UserUserList
 * @package prime\models\permissions
 * @property UserList $userList
 * @property int $userListId
 * @property User $user
 * @property int $userId
 */
class UserUserList extends Permission
{
    public function init()
    {
        $this->source = User::class;
        $this->target = UserList::class;
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        $result = parent::find();
        $result->andWhere([
            'target' => UserList::class,
            'source' => User::class
        ]);
        return $result;
    }

    public static function grant(\yii\db\ActiveRecord $source, \yii\db\ActiveRecord $target, $permission, $strict = false)
    {
        vd('test');
        if(!($source instanceOf User)) {
            throw new \DomainException('Source should be instance of ' . User::class);
        }

        if(!($target instanceOf UserList)) {
            throw new \DomainException('Target should be instance of ' . Workspace::class);
        }

        return parent::grant($source, $target, $permission, $strict);
    }

    public function getUserList()
    {
        return $this->hasOne(UserList::class, ['id' => 'target_id']);
    }

    public function getUserListId()
    {
        return $this->target_id;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'source_id']);
    }

    public function getUserId()
    {
        return $this->source_id;
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['source_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
                [['target_id'], 'exist', 'targetClass' => UserList::class, 'targetAttribute' => 'id'],
                [['source_id'], 'in', 'not' => true, 'range' => [$this->userList->user_id]]
            ]
        );
    }

    public function setUserListId($value)
    {
        $this->target_id = $value;
    }

    public function setUserId($value)
    {
        $this->source_id = $value;
    }
}