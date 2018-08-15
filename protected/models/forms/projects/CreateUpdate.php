<?php

namespace prime\models\forms\projects;

use prime\models\Country;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use prime\models\permissions\Permission;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\web\JsExpression;

class CreateUpdate extends Project
{
    public function scenarios()
    {
        $scenarios =  [
            'create' => [
                'title',
                'owner_id',
                'data_survey_eid',
                'token'
            ],
              'update' => [
                'title',
                'description',
            ],
        ];
        $scenarios['admin-update'] = array_merge(['owner_id'], $scenarios['update']);
        return $scenarios;
    }

    public static function tableName()
    {
        return Project::tableName();
    }
}