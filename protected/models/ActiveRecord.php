<?php

namespace prime\models;

use prime\components\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public const SCENARIO_SEARCH = 'search';

    public static function find()
    {
        return new ActiveQuery(self::class);
    }


    public function beforeSave($insert)
    {
        if ($this->scenario === self::SCENARIO_SEARCH) {
            throw new \Exception('Cannot save a model that was meant for search');
        }
        return parent::beforeSave($insert);
    }

    public function attributeLabels(): array
    {
        return [
            'id' => \Yii::t('app', 'Id'),
            'title' => \Yii::t('app', 'Title'),
            'created' => \Yii::t('app', 'Created at'),
            'created_at' => \Yii::t('app', 'Created at'),
            'last_login_at' => \Yii::t('app', 'Last login at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
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