<?php

namespace prime\models\permissions;

use prime\components\ActiveQuery;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use yii\helpers\ArrayHelper;

/**
 * Class UserProject
 * @package prime\models\permissions
 * @property Workspace $project
 * @property int $projectId
 * @property User $user
 * @property int $userId
 */
class UserProject extends Permission
{
    public function init()
    {
        $this->source = User::class;
        $this->target = Workspace::class;
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        $result = parent::find();
        $result->andWhere([
            'target' => Workspace::class,
            'source' => User::class
        ]);
        return $result;
    }

    public static function grant(\yii\db\ActiveRecord $source, \yii\db\ActiveRecord $target, $permission, $strict = false)
    {
        if(!($source instanceOf User)) {
            throw new \DomainException('Source should be instance of ' . User::class);
        }

        if(!($target instanceOf Workspace)) {
            throw new \DomainException('Target should be instance of ' . Workspace::class);
        }

        return parent::grant($source, $target, $permission, $strict);
    }

    public function getProject()
    {
        return $this->hasOne(Workspace::class, ['id' => 'target_id']);
    }

    public function getProjectId()
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
                [['target_id'], 'exist', 'targetClass' => Workspace::class, 'targetAttribute' => 'id'],
                [['source_id'], 'in', 'not' => true, 'range' => [$this->project->owner_id]]
            ]
        );
    }

    public function setProjectId($value)
    {
        $this->target_id = $value;
    }

    public function setUserId($value)
    {
        $this->source_id = $value;
    }
}