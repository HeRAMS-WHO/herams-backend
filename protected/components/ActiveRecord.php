<?php

namespace prime\components;

use prime\models\permissions\Permission;
use prime\models\ar\User;
use yii\web\HttpException;

class ActiveRecord extends \Befound\ActiveRecord\ActiveRecord
{
    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        $class = "app\\queries\\" . (new \ReflectionClass(get_called_class()))->getShortName() . 'Query';
        if (!class_exists($class)) {
            $class = ActiveQuery::class;
        }
        return \Yii::createObject($class, [get_called_class()]);
    }

    public function loadFromAttributeData()
    {
        $criteria = array_filter($this->attributes, function($value) {return $value !== null;});
        $results = $this::findAll($criteria);

        switch(count($results)) {
            case 0:
                $result = $this;
                break;
            case 1:
                $result = $results[0];
                break;
            default:
                throw new \Exception('Multiple records found');
        }

        return $result;
    }

    /**
     * Loads a model, throws an exception if user does not have permission.
     * @param int $id
     * @param string $priv
     * @param array $with
     * @return static ::class
     * @throws HttpException
     */
    public static function loadOne($id, $priv = Permission::PERMISSION_READ, $with = []) {
        $result = static::find()->where(['id' => $id])->with($with)->userCan($priv)->one();
        if (!isset($result)) {
            throw new HttpException(404, \yii\helpers\StringHelper::basename(static::class) . " not found.");
        }
        return $result;
    }

    public function userCan($operation, User $user = null)
    {
        $user = (isset($user)) ? (($user instanceof User) ? $user : User::findOne($user)) : app()->user->identity;
        return $user->isAdmin;
    }
}