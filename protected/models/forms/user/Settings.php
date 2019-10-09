<?php

namespace prime\models\forms\user;

use dektrium\user\Mailer;
use dektrium\user\models\SettingsForm;
use dektrium\user\Module;
use yii\helpers\ArrayHelper;

class Settings extends SettingsForm
{
    /**
     * @var string
     */
    public $confirm_new_password;

    public function __construct(Mailer $mailer, $config = [])
    {
        $this->mailer = $mailer;
        $this->setAttributes([
            'email'    => $this->user->unconfirmed_email ?: $this->user->email,
        ], false);

        if (!empty($config)) {
            Yii::configure($this, $config);
        }
        $this->init();
    }

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

    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->password = $this->new_password;
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }

            return $this->user->save();
        }

        return false;
    }
}