<?php
declare(strict_types=1);

namespace prime\models;

use prime\components\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public const SCENARIO_UPDATE = 'update';

    /**
     * @codeCoverageIgnore
     */
    public static function find(): ActiveQuery
    {
        return new ActiveQuery(static::class);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function labels(): array
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
     * @codeCoverageIgnore
     */
    final public function attributeLabels(): array
    {
        return static::labels();
    }

    /**
     * Returns a field useful for displaying this record
     * @return string
     */
    public function getDisplayField(): string
    {
        foreach (['title', 'name', 'email'] as $attribute) {
            if ($this->hasAttribute($attribute) && !empty($result = $this->getAttribute($attribute))) {
                return $result;
            }
        }

        $pk = implode(', ', $this->getPrimaryKey(true));
        return "No title for " . get_class($this) . "($pk)";
    }
}
