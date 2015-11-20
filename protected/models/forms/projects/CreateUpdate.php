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
    private $_countriesIds;

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->unlinkAll('projectCountries', true);
        foreach ($this->_countriesIds as $countryId) {
            if(!(new ProjectCountry([
                    'project_id' => $this->id,
                    'country_iso_3' => $countryId,
                ])
            )->save(false)) {
                throw new \Exception('Saving countries failed');
            };
        }
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'countriesIds' => \Yii::t('app', 'Countries')
        ]);
    }

    public function getCountriesIds()
    {
        if(!isset($this->_countriesIds)) {
            $this->_countriesIds = ArrayHelper::getColumn($this->getCountries(), 'iso_3');
        }

        return $this->_countriesIds;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['countriesIds', RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3'), 'allowArray' => true],
                // Locality_name is required if there are no countries selected
                [['locality_name', 'latitude', 'longitude'], RequiredValidator::class, 'when' => function(self $model, $attribute) {return empty($model->_countriesIds);}, 'whenClient' => new JsExpression('function(attribute, value){debugger; return $(attribute.input).closest("form").find("[name*=\'[countriesIds]\']").last().val() == null;}'), 'message' => \Yii::t('app', '{attribute} cannot be blank when no Countries are set.')]
            ]
        );
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'default_generator', 'countriesIds', 'latitude', 'longitude', 'locality_name'],
            'update' => ['title', 'description', 'default_generator', 'countriesIds', 'latitude', 'longitude', 'locality_name'],
        ];
    }

    public function setCountriesIds($value)
    {
        $this->_countriesIds = !empty($value) ? $value : [];
    }

    public static function tableName()
    {
        return Project::tableName();
    }
}