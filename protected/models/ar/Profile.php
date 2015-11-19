<?php

namespace prime\models\ar;

use prime\models\Country;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;

class Profile extends \dektrium\user\models\Profile
{
    public function countryOptions()
    {
        $countries = Country::findAll();
        $countries = ArrayHelper::map($countries, 'iso_3', 'name');
        asort($countries);
        return $countries;
    }

    public function getCountryObject()
    {
        return Country::findOne($this->country);
    }

    public function rules()
    {
        //$rules = parent::rules();
        $rules = [];
        $rules[] = [['first_name', 'last_name', 'organization', 'office', 'country'], 'required'];
        $rules[] = [['first_name', 'last_name', 'organization', 'office'], 'string'];
        $rules[] = [['gravatar_email'], 'email'];
        $rules[] = [['country'], RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3')];
        return $rules;
    }
}