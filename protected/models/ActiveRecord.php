<?php

namespace prime\models;

use prime\components\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public const SCENARIO_SEARCH = 'search';
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

    public function beforeSave($insert)
    {
        if ($this->scenario === self::SCENARIO_SEARCH) {
            throw new \Exception('Cannot save a model that was meant for search');
        }
        return parent::beforeSave($insert);
    }

    /**
     * Returns a field useful for displaying this record
     * @return string
     */
    public function getDisplayField(): string
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