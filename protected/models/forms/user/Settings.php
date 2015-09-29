<?php

namespace prime\models\forms\user;

use dektrium\user\models\SettingsForm;
use yii\helpers\ArrayHelper;

class Settings extends SettingsForm
{
    /**
     * @var string
     */
    public $confirm_new_password;

    public function rules()
    {
        $result = ArrayHelper::merge(parent::rules(),
            [
                ['confirm_new_password', 'compare', 'compareAttribute' => 'new_password']
            ]
        );
        unset($result['usernameRequired']);
        return $result;
    }
}