<?php

namespace prime\models;

use prime\components\ActiveQuery;
use prime\injection\SetterInjectionInterface;
use prime\injection\SetterInjectionTrait;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use yii\web\HttpException;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_CREATE = 'create';
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
     * Returns a field useful for displaying this record
     * @return string
     */
    public function getDisplayField()
    {
        foreach(['title', 'name'] as $attribute) {
            if ($this->hasAttribute($attribute)) {
                return $this->getAttribute($attribute);
            }
        }

        $pk = $this->getPrimaryKey();
        if (is_array($pk))
        {
            $pk = print_r($pk, true);
        }


        return "No title for " . get_class($this) . "($pk)";
    }
}