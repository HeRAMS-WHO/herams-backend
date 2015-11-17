<?php

namespace prime\models\forms\projects;

use prime\models\Country;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\SafeValidator;

class CreateUpdate extends Project
{
    private $_countriesIds = [];

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
                ['countriesIds', SafeValidator::class],
                ['countriesIds', RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3'), 'allowArray' => true]
            ]
        );
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'default_generator', 'countriesIds'],
            'update' => ['title', 'description', 'default_generator', 'countriesIds'],
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