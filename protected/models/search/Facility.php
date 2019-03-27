<?php

namespace prime\models\search;

use app\queries\ProjectQuery;
use prime\components\ActiveQuery;
use prime\models\ar\Project;
use prime\models\Country;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\StringValidator;

class Facility extends Model
{
    public function __construct(
        ?int $toolId,
        array $config = []
    ) {
        parent::__construct($config);
        $this->_toolId = $toolId;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'countryIds' => \Yii::t('app', 'Tool'),
        ]);
    }




    public function rules()
    {
        return [
            [['created', 'closed'], 'safe'],
            [['tool_id'], RangeValidator::class, 'range' => array_keys($this->toolsOptions()), 'allowArray' => true],
            [['title', 'description', 'tool_id', 'locality_name'], StringValidator::class],
        ];
    }



}