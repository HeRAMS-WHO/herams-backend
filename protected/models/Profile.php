<?php

namespace prime\models;

class Profile extends \dektrium\user\models\Profile
{
    public function rules()
    {
        //$rules = parent::rules();
        $rules = [];
        $rules[] = [['first_name', 'last_name', 'organization', 'office', 'country'], 'required'];
        $rules[] = [['first_name', 'last_name', 'organization', 'office', 'country'], 'string'];
        $rules[] = [['gravatar_email'], 'email'];
        return $rules;
    }

}