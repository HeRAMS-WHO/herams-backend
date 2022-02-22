<?php

namespace prime\models\forms\user;

use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\validators\CompareValidator;
use yii\validators\RequiredValidator;

class ResetPasswordForm extends Model
{
    /**
     * @var User
     */
    private $user;
    public $password;
    public $password_repeat;

    public function __construct(User $user, array $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
    }


    public function rules()
    {
        return [
            [['password', 'password_repeat'], RequiredValidator::class],
            ['password', StrengthValidator::class, 'usernameValue' => $this->user->email, 'preset' => StrengthValidator::NORMAL],
            [['password_repeat'], CompareValidator::class, 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('app', 'New password'),
            'password_repeat' => \Yii::t('app', 'Repeat password'),
        ];
    }

    public function resetPassword()
    {
        if (!$this->validate()) {
            throw new InvalidConfigException(\Yii::t('app', 'Validation failed'));
        }

        $this->user->updatePassword($this->password);
    }
}
