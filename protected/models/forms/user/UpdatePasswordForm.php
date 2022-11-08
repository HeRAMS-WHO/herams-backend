<?php

declare(strict_types=1);

namespace prime\models\forms\user;

use herams\common\domain\user\User;
use kartik\password\StrengthValidator;
use yii\base\Model;
use yii\validators\CompareValidator;

class UpdatePasswordForm extends Model
{
    public string $currentPassword = '';

    public string $newPassword = '';

    public string $newPasswordRepeat = '';

    public function __construct(
        private User $user,
        array $config = []
    ) {
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'currentPassword' => \Yii::t('app', 'Current password'),
            'newPassword' => \Yii::t('app', 'New password'),
            'newPasswordRepeat' => \Yii::t('app', 'Repeat password'),
        ];
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function rules(): array
    {
        return [
            [['newPasswordRepeat'],
                CompareValidator::class,
                'compareAttribute' => 'newPassword',
            ],
            [['newPassword'],
                StrengthValidator::class,
                'usernameValue' => $this->user->email,
                'preset' => 'normal',
            ],
            [['currentPassword'], function ($attribute, $params, $validator) {
                if (! password_verify($this->currentPassword, $this->user->password_hash)) {
                    $this->addError($attribute, "Incorrect password");
                }
            }],
        ];
    }

    public function run(): void
    {
        $this->user->setPassword($this->newPassword);
        $this->user->updateAttributes(['password_hash']);
    }
}
