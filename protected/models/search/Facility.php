<?php

namespace prime\models\search;

use yii\base\Model;
use yii\validators\RangeValidator;
use yii\validators\StringValidator;

class Facility extends Model
{
    public function __construct(
        ?int $projectId,
        array $config = []
    ) {
        parent::__construct($config);
        $this->_toolId = $projectId;
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