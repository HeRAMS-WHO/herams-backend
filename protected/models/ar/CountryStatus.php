<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "{{%country_status}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status_id
 * @property string $geodata
 * @property string $stats
 */
class CountryStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status_id' => Yii::t('app', 'Status ID'),
            'geodata' => Yii::t('app', 'Geo Data'),
            'stats' => Yii::t('app', 'Stats'),
        ];
    }
}

