<?php

namespace prime\models\forms\projects;

use prime\models\Country;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
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
                ['token', DefaultValueValidator::class, 'value' => app()->security->generateRandomString(35)]

            ]
        );
    }

    public function scenarios()
    {
        return [
            'create' => [
                'title',
                'description',
                'owner_id',
                'data_survey_eid',
                'tool_id',
                'default_generator',
                'country_iso_3',
                'latitude',
                'longitude',
                'locality_name',
                'token'
            ],
            'update' => ['title', 'description', 'default_generator', 'country_iso_3', 'latitude', 'longitude', 'locality_name'],
        ];
    }

    public static function tableName()
    {
        return Project::tableName();
    }
}