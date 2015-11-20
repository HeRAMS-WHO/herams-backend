<?php

namespace prime\models\forms\projects;

use prime\models\Country;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\web\JsExpression;

class CreateUpdate extends Project
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [

            ]
        );
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'default_generator', 'country_iso_3', 'latitude', 'longitude', 'locality_name'],
            'update' => ['title', 'description', 'default_generator', 'country_iso_3', 'latitude', 'longitude', 'locality_name'],
        ];
    }

    public static function tableName()
    {
        return Project::tableName();
    }
}