<?php

namespace prime\models\ar;

use prime\models\Country;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;

class Profile extends \dektrium\user\models\Profile
{
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'office' => \Yii::t('app', 'Location'),
            'phone_alternative' => \Yii::t('app', 'Alternative phone'),
            'other_contact' => \Yii::t('app', 'Other contact point (e.g. Skype)')
        ]);
    }

    public function getAccessToken()
    {
        return $this->user->access_token;
    }

    public function rules()
    {
        $rules = parent::rules();
//        $rules = [];
        $rules[] = [['first_name', 'last_name', 'organization', 'country'], 'required'];
        $rules[] = [['first_name', 'last_name', 'organization', 'office', 'position', 'other_contact'], 'string'];
        $rules[] = [['gravatar_email'], 'email'];
        $rules[] = [['phone', 'phone_alternative'], RegularExpressionValidator::class, 'pattern' => '/^\+?\d{4,20}$/', 'message' => \Yii::t('app', 'Please enter a valid phone number')];
        return $rules;
    }

    public function getName()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}