<?php

namespace app\models\ar;

use Yii;


/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property text $json_template
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
            [['name', 'layout'], 'string'],
            [['json_template', 'ws_chart_url', 'ws_map_url'], 'text'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'name' => Yii::t('app', 'Name'),
            'json_template' => Yii::t('app', 'Json Template'),
            'ws_chart_url' => Yii::t('app', 'WS Chart URL'),
            'ws_map_url' => Yii::t('app', 'WS Map URL'),
            'layout' => Yii::t('app', 'Layout'),
        ];
    }

}

